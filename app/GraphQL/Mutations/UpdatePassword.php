<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helpers\Password;
use App\Models\User;

final readonly class UpdatePassword
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        if (!auth()->user()) {
            return ["message" => "You are not logged in", "status" => 403];
        }

        $user = User::find(auth()->user()->id);

        $login = auth()->attempt(['email' => $user->email, 'password' => $args['password']]);

        if (!$login) {
            return ["message" => "Password not match", "status" => 403];
        }

        $check_password = Password::check($args['new_password'], $args['password_confirmation']);

        if (!$check_password['ok']) {
            return ["message" => $check_password['message'], "status" => 403];
        }

        $user->password = bcrypt($args['new_password']);

        $user->save();

        return ["message" => "Your password has be updated", "status" => 200];
    }
}
