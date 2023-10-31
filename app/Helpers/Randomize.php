<?php

namespace App\Helpers;

class Randomize {


    public static function quickRandom($length = 40, $onlyInteger=false)
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($onlyInteger){
            $pool = '0123456789';
        }
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}