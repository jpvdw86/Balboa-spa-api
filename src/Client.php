<?php

namespace Jpvdw\Balboa;

use Jpvdw\Balboa\Model\Device;
use Jpvdw\Balboa\Model\Panel;
use Jpvdw\Balboa\Service\Buttons;
use Jpvdw\Balboa\Service\Temperature;

class Client
{
    private string $deviceId;
    private string $bearerToken;

    public function __construct(string $deviceId, string $bearerToken)
    {
        $this->deviceId = $deviceId;
        $this->bearerToken = $bearerToken;
    }


    public function getDevice(): Device
    {
        $postString = "<sci_request version=\"1.0\"><file_system cache=\"false\"><targets><device id=\"{$this->deviceId}\"/></targets><commands><get_file path=\"DeviceConfiguration.txt\" syncTimeout=\"15\"/></commands></file_system></sci_request>";
        $data = Api::postXml($postString, $this->bearerToken);
        return new Device($data);
    }

    public function getPanel(): ?Panel
    {
        $postString = "<sci_request version=\"1.0\"><file_system cache=\"false\"><targets><device id=\"{$this->deviceId}\"/></targets><commands><get_file path=\"PanelUpdate.txt\" syncTimeout=\"15\"/></commands></file_system></sci_request>";
        $data = Api::postXml($postString, $this->bearerToken);
        return new Panel($data);
    }

    public function getButtonActions(): ?Buttons
    {
        return new Buttons($this->deviceId, $this->bearerToken);
    }

    public function getTemperatureActions(): ?Temperature
    {
        return new Temperature($this->deviceId, $this->bearerToken);
    }

}