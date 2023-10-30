<?php

namespace Tests\Feature;

use App\Models\Session;
use App\Models\User;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use MakesGraphQLRequests;
    use RefreshesSchemaCache;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // register user
        $this->graphQL(
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
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@gmail.com',
                'password' => 'Password@123456',
                'password_confirmation' => 'Password@123456',
            ]
        );

        // login user and send email
        $this->graphQL(
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
                'email' => 'johndoe@gmail.com',
                'password' => 'Password@123456',
            ]
        );

        $user = User::where('email', 'johndoe@gmail.com')->first();
        $session = Session::where('user_id', $user->id)->where('action', 'MULTI_FACTORS')->first();
        
        // login with multi factors
        $response = $this->graphQL(
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
                'email' => 'johndoe@gmail.com',
                'token' => $session->token,
            ]
        );

        $status = $response->json("data.login.status");
        
        Session::where('user_id', $user->id)->forceDelete();
        $user->forceDelete();

        $this->assertSame(200, intval($status));
    }
}
