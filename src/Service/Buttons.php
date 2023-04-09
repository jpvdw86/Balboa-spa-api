<?php

namespace Jpvdw\Balboa\Service;

use Jpvdw\Balboa\Api;

class Buttons
{
    private string $deviceId;
    private string $bearerToken;

    public function __construct(string $deviceId, string $bearerToken)
    {
        $this->deviceId = $deviceId;
        $this->bearerToken = $bearerToken;
    }
    
    public function toggleBlowers(): bool
    {
        return $this->toggleAction(17);
    }

    public function togglePump2(): bool
    {
        return $this->toggleAction(5);
    }


    public function togglePump1(): bool
    {
        return $this->toggleAction(4);
    }

    public function toggleLights(): bool
    {
        return $this->toggleAction(17);
    }


    private function toggleAction(int $buttonId): bool
    {
        $postString = "<sci_request version=\"1.0\"><data_service><targets><device id=\"{$this->deviceId}\"/></targets><requests><device_request target_name=\"Button\">{$buttonId}</device_request></requests></data_service></sci_request>";
        $data = Api::postXml($postString, $this->bearerToken);
        return (strpos($data, 'Command received') !== false);
    }
}