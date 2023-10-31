<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\Password;
use App\Models\Session;
use App\Models\User;
use App\Helpers\Email;
use App\Helpers\Randomize;

final readonly class Register
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // check email 
        $user = User::where('email', $args['email'])->first();

        if ($user) {
            return ["message" => "Email already registered", "status" => 403];
        }

        $check_password = Password::check($args['password'], $args['password_confirmation']);

        if (!$check_password['ok']) {
            return ["message" => $check_password['message'], "status" => 403];
        }

        $user = new User();
        $user->password = bcrypt($args['password']);
        $user->first_name = $args['first_name'];
        $user->last_name = $args['last_name'];
        $user->email = $args['email'];

        $user->save();

        $token = auth()->attempt(['email' => $args['email'], 'password' => $args['password']]);

        if ($token) {
            $login = new Session();
            $login->user_id = auth()->user()->id;
            $login->action = "REGISTER";
            $login->save();

            $random = Randomize::quickRandom(54);

            $login = new Session();
            $login->token = $random;
            $login->user_id = $user->id;
            $login->action = "CONFIRM_EMAIL";
            $login->expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));
            $login->save();

            Email::sender($user->email, [
                'subject' => "Welcome to Z",
                'title' => "Welcome to Z",
                'content' => "we're committed to bringing you the most advanced and convenient payment solutions tailored for the African continent. We understand that Africa is a diverse and dynamic landscape with unique challenges, and we're here to address them head-on.",
                'btn_label' => "Confirm email",
                'btn_url' => env('CLIENT_URL') . "/web-hook?action=verify_email&token={$random}&email={$user->email}",
                'footer' => "With love from Z"
            ]);

            // send email verify
            new SendEmailVerify($_, $args);

            return ["message" => "We have sent an email confirmation link", "status" => 200, "token" => $token];
        }

        return ["message" => "Something goes wrong", "status" => 403];
    }
}
