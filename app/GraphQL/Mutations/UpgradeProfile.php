<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class UpgradeProfile
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::where(auth()->user()->id)->where('role', 'ADMIN')->first();
        
        if(!$user){
            return ["message" => "You don't have enough permissions to proceed this action", "status" => 403];
        }

        $user = User::find($args['id']);

        if(!$user){
            return ["message" => "User not found", "status" => 403];
        }

        if(isset($args['status'])){
            $user->first_name = $args['status'];
        }

        if(isset($args['role'])){
            $user->last_name = $args['role'];
        }

        $user->save();

        // send email

        return ["message" => "Profile has been updated", "status" => 200, 'data' => $user];

    }
}
