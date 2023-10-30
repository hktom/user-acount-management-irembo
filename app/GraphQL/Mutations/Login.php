<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Session;
use App\Models\User;

final readonly class Login
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $multi_factor = $args['token'];

        $user = User::where('email', $args['email'])->first();

        if (!$user) {
            return ["message" => "Email not found", "status" => 403];
        }

        $login = Session::where('token', $multi_factor)
        ->where('action', 'MULTI_FACTORS')
        ->where('user_id', $user->id)
        ->where('expires_at', '>=', date('Y-m-d H:i:s'))
        ->first();

        if (!$login) {
            return ["message" => "Token not found", "status" => 403];
        }

        $token = auth()->login($user);
        $login->delete();
        return ["message" => "Login success", "status" => 200, "token" => $token];
    }
}
