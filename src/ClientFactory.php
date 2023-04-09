<?php

namespace Jpvdw\Balboa;

class ClientFactory
{
    protected ?Client $client = null;

    public function create(string $userName, string $password): Client
    {
        if(!$this->client){
            $this->client = $this->init($userName, $password);
        }
        return $this->client;
    }

    public function init(string $userName, string $password): Client
    {
        $data = Api::getBearerTokenAndDeviceId($userName, $password);

        return new Client($data['device']['device_id'], $data['token']);
    }

}