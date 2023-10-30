<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\Randomize;
use App\Models\Session;
use App\Models\User;
use App\Helpers\Email;

final readonly class ForgotPassword
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find($args['email']);
        if(!$user){
            return ["message" => "Email not found", "status" => 403];
        }

        $token = Randomize::quickRandom(54);
        $login = new Session();
        $login->token = $token;
        $login->expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $login->user_id = $user->id;
        $login->action = "FORGOT_PASSWORD";
        $login->save();

        Email::sender($args['email'], [
            'subject' => "Z Authentication reset password",
            'title' => "Z Authentication reset password",
            'content' => "We received a request to reset your password for your account with [Your Company Name]. To complete the password reset process, please follow the instructions below. If you didn't request a password reset, or if you believe this request was made in error, please disregard this email. Your account remains secure. It will expire in 1 hour.",
            'btn_label' => "Reset Password",
            'btn_url' => env('CLIENT_URL')."/auth/reset_password?action=magic_link&token=$token&email={$args['email']}",
            'footer' => "With love from Z"
        ]);

        return ["message" => "We have sent an email with the reset link", "status" => 200];
    }
}
