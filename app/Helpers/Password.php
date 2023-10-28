<?php

namespace App\Helpers;

class Password
{


    public static function check(string $password, string $confirm_password): array
    {
        if ($password !== $confirm_password) return ['ok' => false, 'message' => 'Password and confirm password does not match'];

        if (strlen($password) < 8) return ['ok' => false, 'message' => 'Password must be at least 8 characters'];

        if (!preg_match("#[0-9]+#", $password)) return ['ok' => false, 'message' => 'Password must include at least one number!'];

        if (!preg_match("#[a-zA-Z]+#", $password)) return ['ok' => false, 'message' => 'Password must include at least one letter!'];

        if (!preg_match("#[A-Z]+#", $password)) return ['ok' => false, 'message' => 'Password must include at least one CAPS!'];

        if (!preg_match("#\W+#", $password)) return ['ok' => false, 'message' => 'Password must include at least one symbol!'];

        return ['ok' => true, 'message' => 'Password is valid'];
    }
}
