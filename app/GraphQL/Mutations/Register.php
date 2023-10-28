<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helper\Randomize;
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

        $nationality = Nationality::find($args['nationality_id']);

        if (!$nationality) {
            return ["message" => "This country is not open yet", "status" => 403];
        }

        $user = new User();
        $user->first_name = $args['first_name'];
        $user->last_name = $args['last_name'];
        $user->gender = $args['gender'];
        $user->date_of_birth = $args['date_of_birth'];
        $user->marital_status = $args['marital_status'];
        $user->email = $args['email'];
        $user->nationality_id = $nationality->id;
            $user->password = bcrypt($args['password']);

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

            return ["message" => "We have sent an email confirmation link", "status" => 200];
        }

        return ["message" => "Something goes wrong", "status" => 403];
    }
}
