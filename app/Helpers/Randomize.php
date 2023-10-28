<?php

namespace App\Helpers;

class Randomize {


    public static function quickRandom($length = 40)
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}