<?php

namespace jpvdw\Balboa\Decoder;

class BaseDecoder {


    /**
     * Convert Message sting to byte array for calculating the state
     * @param $message
     * @return array
     */
    public function convertToBytes($message){
        $output = [];
        for($i = 0; $i < strlen($message); $i++){
            $output[]  = ord($message[$i]);
        }
        return $output;
    }

}