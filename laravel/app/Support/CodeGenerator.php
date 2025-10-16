<?php

namespace App\Support;

class CodeGenerator
{
    public static function make(int $length = 6): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';
        $code = '';
        for ($i=0; $i<$length; $i++) {
            $code .= $alphabet[random_int(0, strlen($alphabet)-1)];
        }
        return $code;
    }
}
