<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\Password;
use App\Models\Session;
use App\Models\User;

final readonly class ResetPassword
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::where('email', $args['email'])->first();
        
        if(!$user){
            return ["message" => "Email not found", "status" => 403];
        }

        $login = Session::where('token', $args['token'])
        ->where('user_id', $user->id)
        ->where('expires_at', '>', date('Y-m-d H:i:s'))
        ->first();

        if(!$login){
            return ["message" => "Token not found or expired", "status" => 403];
        }

        $check_password = Password::check($args['password'], $args['password_confirmation']);

        if (!$check_password['ok']) {
            return ["message" => $check_password['message'], "status" => 403];
        }

        
        $user->password = bcrypt($args['password']);
        $user->save();

        $login->delete();
        
        return ["message" => "Password has been changed", "status" => 200];
    }
}
