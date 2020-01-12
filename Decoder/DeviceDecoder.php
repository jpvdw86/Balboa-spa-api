<?php

namespace jpvdw\Balboa\Decoder;

use jpvdw\Balboa\Decoder\BaseDecoder;

class DeviceDecoder extends BaseDecoder
{
    protected $deviceArray = [];

    /**
     * DeviceDecoder constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->deviceArray = $this->convertToBytes($message);
    }

    /**
     * @return bool
     */
    public function hasPump0(){
        return ($this->deviceArray[8] & 128) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasPump1(){
        return ($this->deviceArray[5] & 3) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasPump2(){
        return ($this->deviceArray[5] & 12) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasPump3(){
        return ($this->deviceArray[5] & 48) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasPump4(){
        return ($this->deviceArray[5] & 192) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasPump5(){
        return ($this->deviceArray[6] & 3) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasPump6(){
        return ($this->deviceArray[6] & 192) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasLight1(){
        return ($this->deviceArray[7] & 3) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasLight2(){
        return ($this->deviceArray[7] & 192) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasAux1(){
        return ($this->deviceArray[9] & 1) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasAux2(){
        return ($this->deviceArray[9] & 2) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasBlower(){
        return ($this->deviceArray[8] & 0) != 0 ? true : false;
    }

    /**
     * @return bool
     */
    public function hasMister(){
        return ($this->deviceArray[9] & 16) != 0 ? true : false;
    }

}