<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\Email;
use App\Models\Session;
use App\Models\User;

final readonly class VerifyEmail
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {

        if (!auth()->user()) {
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

        Email::sender($user->email, [
            'subject' => "Z Email Verification",
            'title' => "Welcome to Z",
            'content' => "Your email has been verified, you can now access all features in Z",
            'btn_label' => "Go to Z",
            'btn_url' => env('CLIENT_URL'),
            'footer' => "With love from Z"
        ]);

        // send email 

        return ["message" => "Email has been verified", "status" => 200, 'user' => $user];
    }
}
