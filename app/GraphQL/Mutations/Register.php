<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\Password;
use App\Helpers\Randomize;
use App\Helpers\Email;
use App\Models\Nationality;
use App\Models\Session;
use App\Models\User;

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

        // $nationality = Nationality::find($args['nationality_id']);

        // if (!$nationality) {
        //     return ["message" => "This country is not open yet", "status" => 403];
        // }
        $user = new User();
        $user->password = bcrypt($args['password']);
        $user->first_name = $args['first_name'];
        $user->last_name = $args['last_name'];
        $user->email = $args['email'];
        // $user->gender = $args['gender'];
        // $user->marital_status = $args['marital_status'];
        // $user->date_of_birth = $args['date_of_birth'];
        // $user->nationality_id = $nationality->id;

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
            $login->user_id = auth()->user()->id;
            $login->action = "CONFIRM_EMAIL";
            $login->save();

            // send email
            $send_email = Email::sender($args['email'], [
                'subject' => "Z  Welcome",
                'title' => "Welcome to Z",
                'content' => "Please confirm your email address to complete your registration",
                'btn_label' => "Confirm email",
                'btn_url' => env('APP_URL') . "/confirm-email?token={$random}&email={$args['email']}",
                'footer' => "With love from Z"
            ]);

            return ["message" => "We have sent an email confirmation link", "status" => 200, "token" => $token];
        }

        return ["message" => "Something goes wrong", "status" => 403];
    }
}
