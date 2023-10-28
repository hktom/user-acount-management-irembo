<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Helper\Randomize;
use App\Models\Session;
use App\Models\User;

final readonly class ForgotPassword
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find($args['email']);
        if(!$user){
            return ["message" => "Email not found", "status" => 403];
        }

        $random = Randomize::quickRandom(54);
        $login = new Session();
        $login->token = $random;
        $login->expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $login->user_id = $user->id;
        $login->action = "FORGOT_PASSWORD";
        $login->save();

        // send email

        return ["message" => "We have sent an email confirmation link", "status" => 200];
    }
}
