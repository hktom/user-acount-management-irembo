<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Session;
use App\Models\User;

final readonly class VerifyEmail
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        if(!auth()->check()) {
            return ["message" => "Unauthorized", "status" => 401];
        }
        
        $session = Session::where('token', $args['token'])->where('action', 'CONFIRM_EMAIL')->first();
        if (!$session) {
            return ["message" => "Token not found or expired", "status" => 403];
        }

        $user = User::find($session->user_id);
        if (!$user) {
            return ["message" => "User not found", "status" => 403];
        }

        $user->email_verified_at = now();
        $user->save();

        $session->delete();

        // send email 

        return ["message" => "Email has been verified", "status" => 200, 'user' => $user];
    }
}
