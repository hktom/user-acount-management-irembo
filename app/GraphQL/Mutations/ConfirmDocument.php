<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class ConfirmDocument
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::where('id', auth()->user()->id)->where('role', 'ADMIN')->first();

        if (!$user) {
            return ["message" => "You don't have enough permissions to proceed this action", "status" => 403];
        }

        $user = User::find($args['id']);
        
        if (!$user) {
            return ["message" => "User not found", "status" => 403];
        }

        $document = $user->document;
        $document->confirmed_at = now();
        $document->save();

        $user->status = 'VERIFIED';
        $user->save();

        // send email

        return ["message" => "Document has been confirmed", "status" => 200, 'data' => $user];
    }
}
