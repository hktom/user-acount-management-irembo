<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Session;

final readonly class Login
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $multi_factor = $args['multi_factor'];
        $login = Session::find($multi_factor);
        if ($login) {
            $token = auth()->loginUsingId($login->user_id);
            $login->delete();
            return ["message" => "Login success", "status" => 200, "token" => $token];
        } else {
            return ["message" => "Token not found", "status" => 403];
        }
    }
}
