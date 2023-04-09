<?php

namespace Jpvdw\Balboa\Service;


use Jpvdw\Balboa\Api;

class Temperature
{
    private string $deviceId;
    private string $bearerToken;

    public function __construct(string $deviceId, string $bearerToken)
    {
        $this->deviceId = $deviceId;
        $this->bearerToken = $bearerToken;
    }

    public function setCelsius(int $temp): bool
    {
        return $this->setTemperature($temp * 2);
    }

    public function setFahrenheit(int $temp): bool
    {
        return $this->setTemperature($temp);
    }

    private function setTemperature(int $temp)
    {
        $postString = "<sci_request version=\"1.0\"><data_service><targets><device id=\"{$this->deviceId}\"/></targets><requests><device_request target_name=\"SetTemp\">{$temp}</device_request></requests></data_service></sci_request>";
        $data = Api::postXml($postString, $this->bearerToken);
        return (strpos($data, 'Command received') !== false);
    }
}