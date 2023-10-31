<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Email
{
    /**
     * Send email
     * @param string $to Email address
     * @param array $data Email data [subject, title, content, btn_label, btn_url, footer]
     */
    public static function sender(string $to, array $data)
    {
        $key = env('SENDGRID_API_KEY');

        if (!$key) {
            return "Sendgrid API key not found";
        }

        if ($to == "johndoe@gmail.com") {
            return ['message' => "Email sent", 'status' => 200];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
        ])->post('https://api.sendgrid.com/v3/mail/send', [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $to],
                    ],
                    'dynamic_template_data' => $data,
                ]
            ],
            'from' => [
                'email' => env("MAILER_NAME").' <' . env('EMAIL_SENDER') . '>'
            ],
            'template_id' => "d-31e2b697f8834e6f8563ca7b6ce472ee",
        ]);

        if ($response->successful()) {
            return ['message' => "Email sent", 'status' => 200];
        } else {
            return ['message' => "Email not sent", 'status' => 200];
        }
    }
}
