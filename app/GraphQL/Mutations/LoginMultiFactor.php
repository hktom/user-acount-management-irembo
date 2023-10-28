<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helper\Randomize;
use App\Models\Session;
use App\Models\User;

final readonly class LoginMultiFactor
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find($args['email']);
        // check attempt
        if($user){
            $attempt_today_in_last_hour = Session::where('user_id', $user->id)->where('action', 'LOGIN_ATTEMPT')->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))->count();
            
            if($attempt_today_in_last_hour >= 5){
                return ["message" => "Too many attempts, please try again later", "status" => 403];
            }
        }

        $token = auth()->attempt(['email' => $args['email'], 'password' => $args['password']]);
        
        // save attempt
        if($user && !$token){
            $login = new Session();
            $login->user_id = $user->id;
            $login->action = "LOGIN_ATTEMPT";
            $login->save();
        }

        if ($token) {

            $multi_factor = Randomize::quickRandom(4);
            $login = new Session();
            $login->user_id = auth()->user()->id;
            $login->token = $multi_factor;
            $login->expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $login->action = "MULTI_FACTORS";
            $login->save();

            return ["message" => "We have sent the confirmation to your email", "status" => 200];
        }

        return ["message" => "Password or email not found", "status" => 403];
    }
}
