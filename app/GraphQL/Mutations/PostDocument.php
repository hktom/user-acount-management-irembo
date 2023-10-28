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

        $user = User::find($args['id']);

        $document = new Document();
        $document->national_id = $args['national_id'];
        $document->document_type = $args['document_type'];
        $document->photo = $args['photo'];
        $document->save();

        $user->status = 'PENDING';
        $user->save();

        // send email

        return ["message" => "Document has been posted", "status" => 200, 'data' => $user];

    }
}
