<?php

namespace Jpvdw\Balboa\Helper;

class Decoder {

    public static function decode($message): array
    {
        $message = mb_convert_encoding($message, 'UTF-8', 'ISO-8859-1');
        $message = base64_decode($message);

        $output = [];
        $output[0] = 126;
        $max = strlen($message);
        for($i = 0; $i < $max; $i++){
            $output[]  = ord($message[$i]);
        }
        return $output;
    }
}