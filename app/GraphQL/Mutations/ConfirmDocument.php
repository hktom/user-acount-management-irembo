<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Helpers\Email;

final readonly class ConfirmDocument
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::where('id', auth()->user()->id)->where('role', 'ADMIN')->first();

        if (!$user) {
            return ["message" => "You don't have enough permissions to proceed this action", "status" => 403];
        }

        $user = User::find($args['user_id']);

        if (!$user) {
            return ["message" => "User not found", "status" => 403];
        }

        $document = $user->document;
        if ($args['status'] == 'VERIFIED') {
            $document->verified_at = now();
        }
        $user->status = $args['status'];
        $document->save();
        $user->save();

        $message = [
            'VERIFIED' => "We are happy to inform you that your document has been verified. You can now proceed to use our services. Thank you for choosing Z.",
            'UNVERIFIED' => "We are sorry to inform you that your document has been rejected. Please contact our support team for more information. Thank you for choosing Z."
        ];

        // send email
        Email::sender($user->email, [
            'subject' => "Official document confirmation update",
            'title' => "Official document confirmation update",
            'content' => $message[$args['status']],
            'btn_label' => "Go to Z",
            'btn_url' => env('CLIENT_URL'),
            'footer' => "With love from Z"
        ]);

        $users = User::all();

        return ["message" => "Document has been confirmed", "status" => 200, 'users' => $users];
    }
}
