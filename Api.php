<?php

namespace jpvdw\balboa;

class Api {

    protected $baseUrl = 'https://bwgapi.balboawater.com';
    protected $token = null;
    protected $deviceId = null;

    /**
     * Api constructor.
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function __construct($username, $password)
    {
        $this->login($username, $password);
    }

    /**
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function login($username, $password){
        $postFields = json_encode([
            "password" => $password,
            "username" => $username,
        ]);

        $result = $this->apiCall('/users/login', 'POST', $postFields, 'application/json');
        if(!isset($result['token']) || !isset($result['device']['device_id'])){
            throw new \Exception("login faild");
        }
        $this->token = $result['token'];
        $this->deviceId = $result['device']['device_id'];
    }

    /**
     * @return bool
     */
    public function blowers(){
        return $this->toggleButton(17);
    }

    /**
     * @return bool
     */
    public function pump2(){
        return $this->toggleButton(5);
    }

    /**
     * @return bool
     */
    public function pump1(){
        return $this->toggleButton(4);
    }

    /**
     * @return bool
     */
    public function toggleLights(){
        return $this->toggleButton(17);
    }


    /**
     * @param $buttonId
     * @return bool
     */
    private function toggleButton($buttonId){
        if(!$this->deviceId || !$this->token){
            return false;
        }

        $postString = "<sci_request version=\"1.0\"><data_service><targets><device id=\"{$this->deviceId}\"/></targets><requests><device_request target_name=\"Button\">{$buttonId}</device_request></requests></data_service></sci_request>";
        $this->apiCall('/devices/sci', 'POST', $postString, 'application/xml');
        return true;
    }

    /**
     * @param $celcius
     * @return bool
     */
    public function setTemp($celcius){
        if(!$this->deviceId || !$this->token){
            return false;
        }
        $tempCode = $celcius * 2;
        $postString = "<sci_request version=\"1.0\"><data_service><targets><device id=\"{$this->deviceId}\"/></targets><requests><device_request target_name=\"SetTemp\">{$tempCode}</device_request></requests></data_service></sci_request>";
        return $this->apiCall('/devices/sci', 'POST', $postString, 'application/xml');

    }

    /**
     * Get all functions. response must go to DeviceDecoder
     * @return bool|mixed|string
     */
    public function getDeviceDetails(){
        if(!$this->deviceId || !$this->token){
            return false;
        }
        $postString = "<sci_request version=\"1.0\"><file_system cache=\"false\"><targets><device id=\"{$this->deviceId}\"/></targets><commands><get_file path=\"DeviceConfiguration.txt\" syncTimeout=\"15\"/></commands></file_system></sci_request>";
        return $this->apiCall('/devices/sci', 'POST', $postString, 'application/xml');

    }

    /**
     * Get current state of the spa. Response must go to PanelDecoder
     * @return bool|mixed|string
     */
    public function getPanelUpdate(){
        if(!$this->deviceId || !$this->token){
            return false;
        }
        $postString = "<sci_request version=\"1.0\"><file_system cache=\"false\"><targets><device id=\"{$this->deviceId}\"/></targets><commands><get_file path=\"PanelUpdate.txt\" syncTimeout=\"15\"/></commands></file_system></sci_request>";
        return $this->apiCall('/devices/sci', 'POST', $postString, 'application/xml');

    }

    /**
     * @param $endpoint
     * @param string $type
     * @param null $params
     * @param null $contentType
     * @return bool|mixed|string
     */
    private function apiCall($endpoint, $type= 'GET', $params = null, $contentType =  null){
        $headers = [];
        $headers[] = 'User-Agent: BWA/4.1 (com.createch-group.balboa; build:10; iOS 13.3.0) Alamofire/4.8.1';
        $headers[] = 'Accept-Language: nl-NL;q=1.0';

        if($contentType){
            $headers[] = 'Content-Type: '.$contentType;
        }
        if($this->token){
            $headers[] = 'Authorization: '.$this->token;
        }

        $ch = curl_init();

        if($params){
            $headers[] = 'Content-Length: '.strlen($params);
        }
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if($contentType == 'application/json'){
            $result = json_decode($result,true);
        }
        return $result;
    }
}