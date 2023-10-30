<?php

namespace Tests\Feature;

use App\Helpers\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_password_one(): void
    {
        $passwordCheck_1 = Password::check('password', 'password2');
        $passwordCheck_2 = Password::check('pass', 'pass');
        $passwordCheck_3 = Password::check('passwordStrong', 'passwordStrong');
        $passwordCheck_4 = Password::check('1234567890', '1234567890');
        $passwordCheck_5 = Password::check('password123456', 'password123456');
        $passwordCheck_6 = Password::check('Password123456', 'Password123456');
        $passwordCheck_7 = Password::check('Password@123456', 'Password@123456');

        $this->assertEquals("Password and confirm password does not match", $passwordCheck_1['message']);
        $this->assertEquals("Password must be at least 8 characters", $passwordCheck_2['message']);
        $this->assertEquals("Password must include at least one number!", $passwordCheck_3['message']);
        $this->assertEquals("Password must include at least one letter!", $passwordCheck_4['message']);
        $this->assertEquals("Password must include at least one CAPS!", $passwordCheck_5['message']);
        $this->assertEquals("Password must include at least one symbol!", $passwordCheck_6['message']);
        $this->assertEquals("Password is valid", $passwordCheck_7['message']);

    }
}
