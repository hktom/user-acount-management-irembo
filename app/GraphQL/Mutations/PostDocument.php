<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Document;
use App\Models\User;

final readonly class PostDocument
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $user = User::find(auth()->user()->id);

        if (!$user) {
            return ["message" => "User not found", "status" => 403];
        }
        // delete current document 
        $document = $user->document;
        if ($document) {
            $document->delete();
        }

        if (Document::where('code', $args['code'])->exists()) {
            return ["message" => "You are trying to submit another one document ID. This is fraud.", "status" => 403];
        }
        $document = new Document();
        $document->code = $args['code'];
        $document->name = $args['name'];
        $document->photo = $args['photo'];
        $document->save();

        $user->document_id = $document->id;
        $user->status = "PENDING VERIFICATION";
        $user->save();

        // send email

        return ["message" => "Your document verification has been sent. Your account is now in PENDING VERIFICATION. We have sent you an email with all details", "status" => 200, 'user' => $user];
    }
}
