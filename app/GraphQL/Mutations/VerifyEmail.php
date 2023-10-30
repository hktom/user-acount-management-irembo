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

        $user = User::where('email', $args['email'])->where('email_verified_at', null)->first();

        if(!$user){
            return ["message" => "Email not found or already verified", "status" => 403];
        }

        $session = Session::where('token', $args['token'])
        ->where('user_id', $user->id)
        ->where('action', 'CONFIRM_EMAIL')
        ->where('expires_at', '>', date('Y-m-d H:i:s'))
        ->first();

        if (!$session) {
            return ["message" => "Token not found or expired", "status" => 403];
        }

        $user->email_verified_at = now();

        $user->save();

        $session->delete();

        Email::sender($user->email, [
            'subject' => "Z Email Verification",
            'title' => "Z Email Verification",
            'content' => "Your email has been verified, you can now access all features in Z",
            'btn_label' => "Go to Z",
            'btn_url' => env('CLIENT_URL'),
            'footer' => "With love from Z"
        ]);

        return ["message" => "Email has been verified", "status" => 200, 'user' => $user];
    }
}
