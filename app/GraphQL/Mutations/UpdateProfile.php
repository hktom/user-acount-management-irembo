<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Nationality;
use App\Models\User;

final readonly class UpdateProfile
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find(auth()->user()->id);

        if (!$user) {
            return ["message" => "User not found", "status" => 403];
        }

        foreach ($args as $key => $value) {

            if (isset($args[$key]) && $args[$key] != "undefined" && $key != "nationality_id") {
                $user->$key = $args[$key];
            }

            if (isset($args['nationality_id'])) {
                $nationality = Nationality::find($args['nationality_id']);
                if ($nationality) {
                    $user->nationality_id = $nationality->id;
                }
            }
        }

        $user->save();

        return ["message" => "Profile has been updated", "status" => 200, 'user' => $user];
    }
}
