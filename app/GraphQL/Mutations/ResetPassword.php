<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Session;

final readonly class ResetPassword
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $login = Session::where('token', $args['token'])->where('expires_at', '>', date('Y-m-d H:i:s'))->first();
        if(!$login){
            return ["message" => "Token not found or expired", "status" => 403];
        }

        $user = $login->user;
        $user->password = bcrypt($args['password']);
        $user->save();

        $login->delete();
        
        return ["message" => "Password has been changed", "status" => 200];
    }
}
