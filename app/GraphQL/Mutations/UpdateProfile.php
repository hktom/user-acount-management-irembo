<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

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

        if (isset($args['first_name'])) {
            $user->first_name = $args['first_name'];
        }

        if (isset($args['last_name'])) {
            $user->last_name = $args['last_name'];
        }

        if (isset($args['photo'])) {
            $user->last_name = $args['photo'];
        }

        if (isset($args['nationality'])) {
            $user->nationality = $args['nationality'];
        }

        if (isset($args['gender'])) {
            $user->gender = $args['gender'];
        }

        if (isset($args['date_of_birth'])) {
            $user->date_of_birth = $args['date_of_birth'];
        }

        if (isset($args['marital_status'])) {
            $user->marital_status = $args['marital_status'];
        }

        $user->save();

        return ["message" => "Profile has been updated", "status" => 200, 'data' => $user];
    }
}
