<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Session;

final readonly class Logout
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = auth()->user();

        if ($user) {
            $login = new Session();
            $login->user_id = $user->id;
            $login->action = "LOGOUT";
            $login->save();
        }

        auth()->logout();
        return ["message" => "Logout success", "status" => 200];
    }
}
