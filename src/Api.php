<?php

namespace Jpvdw\Balboa;

class Api {

    public static function getBearerTokenAndDeviceId(string $userName, string $password): array
    {
        $postFields = json_encode(["username" => $userName, "password" => $password]);

        $headers = [
            'User-Agent: BWA/4.1 (com.createch-group.balboa; build:10; iOS 13.3.0) Alamofire/4.8.1',
            'Accept-Language: nl-NL;q=1.0',
        ];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: '.strlen($postFields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bwgapi.balboawater.com/users/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result, true);
        if(!isset($result['token'], $result['device']['device_id'])){
            throw new \RuntimeException('Login Failed');
        }

        return $result;
    }

    public static function postXml(string $xmlString, string $bearerToken): string
    {
        $headers = [
            'User-Agent: BWA/4.1 (com.createch-group.balboa; build:10; iOS 13.3.0) Alamofire/4.8.1',
            'Accept-Language: nl-NL;q=1.0',
        ];
        $headers[] = 'Content-Type: application/xml';
        $headers[] = 'Authorization: '.$bearerToken;
        $headers[] = 'Content-Length: '.strlen($xmlString);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bwgapi.balboawater.com/devices/sci');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        preg_match(
            '~<sci_reply version="1.0"><file_system><device id="(.*?)"><commands><get_file><data>(.*?)</data></get_file></commands></device></file_system></sci_reply>~',
            $result,
            $matches
        );

        return $matches[2] ?? $result;
    }
}