<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Helpers\Randomize;
use App\Helpers\Email;
use App\Models\Session;

final readonly class LoginLink
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::where('email', $args['email'])->first();

        if(!$user){
            return ["message" => "Email not found", "status" => 403];
        }

        Session::where('user_id', $user->email)->where('action', 'MULTI_FACTORS')->delete();

        $multi_factor = Randomize::quickRandom(50);
        $login = new Session();
        $login->user_id = $user->id;
        $login->token = $multi_factor;
        $login->expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $login->action = "MULTI_FACTORS";
        $login->save();

        Email::sender($args['email'], [
            'subject' => "Z Authentication with magic link",
            'title' => "Z Authentication with magic link",
            'content' => "You can login to your account with this link on the button. Do not share this magic link with anyone. It's for your personal use only. It will expire in 1 hour.",
            'btn_label' => "Login to Z",
            'btn_url' => env('CLIENT_URL')."/web-hook?action=magic_link&token=$multi_factor&email={$args['email']}",
            'footer' => "With love from Z"
        ]);

        return ["message" => "We have sent a magic link to your email", "status" => 200];
    }
}
