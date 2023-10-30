<?php

namespace Tests\Feature;

use App\Models\Session;
use App\Models\User;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use MakesGraphQLRequests;
    use RefreshesSchemaCache;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'Password@123456',
            'password_confirmation' => 'Password@123456',
        ];

        // register user
        $register = $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($first_name: String!, $last_name:String!, $email: String!, $password: String!, $password_confirmation: String!) {
                register(first_name: $first_name, last_name: $last_name, email: $email, password: $password, password_confirmation: $password_confirmation) {
                    message
                    status
                    token
                }
            }
        ',
            $payload
        );

        // login user and send email
        $login_multifactores = $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($email: String!, $password: String!) {
                loginMultiFactor(email: $email, password: $password) {
                    message
                    status
                    token
                }
            }
        ',
            [
                'email' => $payload['email'],
                'password' => $payload['password'],
            ]
        );

        $user = User::where('email', $payload['email'])->first();
        $session = Session::where('user_id', $user->id)->where('action', 'MULTI_FACTORS')->first();

        // login with multi factors
        $login = $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($token: String!, $email: String!) {
                login(token: $token, email: $email) {
                    message
                    status
                    token
                }
            }
        ',
            [
                'email' => $payload['email'],
                'token' => $session->token,
            ]
        );

        // login with magic link
        $loginLink = $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($email: String!) {
                loginLink(email: $email) {
                    message
                    status
                }
            }
        ',
            [
                'email' => $payload['email'],
            ]
        );

        // forgotPassword
        $forgotPassword = $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($email: String!) {
                forgotPassword(email: $email) {
                    message
                    status
                }
            }
        ',
            [
                'email' => $payload['email'],
            ]
        );

        // resetPassword
        $session = Session::where('user_id', $user->id)->where('action', 'FORGOT_PASSWORD')->first();
        $resetPassword = $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($token: String!, $password: String!, $password_confirmation: String!, $email: String!) {
                resetPassword(token: $token, password: $password, password_confirmation: $password_confirmation, email: $email) {
                    message
                    status
                }
            }
        ',
            [
                'token' => $session->token,
                'password' => 'Password@123456',
                'password_confirmation' => 'Password@123456',
                'email' => $payload['email'],
            ]
        );

        Session::where('user_id', $user->id)->forceDelete();
        $user->forceDelete();

        $this->assertSame(200, intval($register->json("data.register.status")));
        $this->assertSame(200, intval($login_multifactores->json("data.loginMultiFactor.status")));
        $this->assertSame(200, intval($login->json("data.login.status")));
        $this->assertSame(200, intval($loginLink->json("data.loginLink.status")));
        $this->assertSame(200, intval($forgotPassword->json("data.forgotPassword.status")));
        $this->assertSame(200, intval($resetPassword->json("data.resetPassword.status")));
    }
}
