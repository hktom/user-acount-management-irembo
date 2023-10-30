<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Helpers\Randomize;
use App\Helpers\Email;
use App\Models\Session;

final readonly class SendEmailVerify
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        if(!auth()->user()){
            return ["message" => "You are not logged in", "status" => 403];
        }

        $user = auth()->user();
        
        $random = Randomize::quickRandom(54);

        $session = Session::where('user_id', $user->id)->where('action', "CONFIRM_EMAIL")->first();
        
        if($session){
            $session->delete();
        }

        $login = new Session();
        $login->token = $random;
        $login->user_id = $user->id;
        $login->action = "CONFIRM_EMAIL";
        $login->expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));
        $login->save();

        // send email
        Email::sender($user->email, [
            'subject' => "Z  Email verification",
            'title' => "Z  Email verification",
            'content' => "
            <p>Thank you for registering with Z. To ensure the security of your account and enjoy the full benefits of our platform, we need to verify your email address.</p>

            <p>Please click on the following link to complete the email verification process:</p>

            <p>Once you've verified your email, you'll be able to: </p>

            <p>
                Access your account securely. <br>
                Update your personal information. <br>
                Send an identity verification <br/>
                Reset your password if needed. <br/>

            </p>

            <P> Please Note:

            The verification link is valid for the next [Time Period, e.g., 24 hours]. After that, you may need to request a new verification email.
            If you didn't register with Z, or you believe this email was sent to you by mistake, please disregard this message.
            If you encounter any issues during the verification process, or if you didn't initiate this request, please contact our support team at [Support Email] for assistance.
            
            Thank you for choosing Z. We look forward to serving you.
            
            Best regards,

            </p>
                        
            ",
            'btn_label' => "Confirm email",
            'btn_url' => env('CLIENT_URL') . "/web-hook?action=verify_email&token={$random}&email={$user->email}",
            'footer' => "With love from Z"
        ]);

        return ["message" => "We have sent an email confirmation link", "status" => 200];
    }
}
