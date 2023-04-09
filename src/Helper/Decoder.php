<?php

namespace Jpvdw\Balboa\Helper;

class Decoder {

    public static function decode($message){
        $message = base64_decode(utf8_encode($message));
        $output = [];
        $output[0] = 126;
        $max = strlen($message);
        for($i = 0; $i < $max; $i++){
            $output[]  = ord($message[$i]);
        }
        return $output;
    }
}