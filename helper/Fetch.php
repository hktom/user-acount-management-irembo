<?php

namespace App\Helper\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;

class Fetch
{
    public $response;
    public $status;
    public $error;
    public $json;
    public $object;


    public function __construct()
    {
    }

    public function getHttp(String $url)
    {
        $response = Http::get($url);
        if ($response->successful()) {
            $this->response = $response;
            $this->status = $response->status();
            $this->json = $response->json();
            $this->object = $response->body();
            return $this;
        } else {
            $this->response = $response;
            $this->status = $response->status();
            $this->error = $response->body();
            return $this;
        }
    }

    public function postHttp(String $url, array $data)
    {
        $response = Http::post($url, $data);
        if ($response->successful()) {
            $this->response = $response;
            $this->status = $response->status();
            $this->json = $response->json();
            $this->object = $response->body();
            return $this;
        } else {
            $this->response = $response;
            $this->status = $response->status();
            $this->error = $response->body();
            return $this;
        }
    }

    public function sendMail(string $to, array $data, string $template)
    {
        $key = env('SENDGRID_API_KEY');

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
                'email' => 'Irembo <no-reply@wakafoodz.com>'
            ],
            'template_id' => $template,
        ]);

        if ($response->successful()) {
            $this->response = $response;
            $this->status = $response->status();
            $this->json = $response->json();
            $this->object = $response->body();
            return $this;
        } else {
            $this->response = $response;
            $this->status = $response->status();
            $this->error = $response->body();
            return $this;
        }
    }
}
