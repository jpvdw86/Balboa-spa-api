<?php

namespace jpvdw\Balboa\Decoder;

use jpvdw\Balboa\Decoder\BaseDecoder;

class PanelDecoder extends BaseDecoder {

    protected $panelArray = [];
    
    public function __construct($message)
    {
        $this->panelArray = $this->convertToBytes($message);
    }

    /**
     * Check of tempature scale is Celsius
     * @return bool
     */
    public function isTemperatureScaleCelsius(){
        if (($this->panelArray[14] & 1) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get type of heating mode
     * @return bool
     */
    public function isHeatingModeHigh(){
        if (($this->panelArray[15] & 4) == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get temperature scale.
     * (Farenheit is not correct in scale. but application use it like this)
     * @return float
     */
    public function getTemperature(){
        if ($this->panelArray[7] == 255) {
            return floatval($this->panelArray[7]);
        } else {
            return $this->isTemperatureScaleCelsius() ? floatval($this->panelArray[7] / 2) : floatval($this->panelArray[7]);
        }
    }

    /**
     * Get Target temperature
     * @return float
     */
    public function getTargetTemperature(){
        if ($this->panelArray[25] == 255) {
            return floatval($this->panelArray[25]);
        } else {
            return $this->isTemperatureScaleCelsius() ? floatval($this->panelArray[25] / 2) : floatval($this->panelArray[25]);
        }
    }

    /**
     * Get filter cycle mode
     * @return string
     */
    public function getFilterMode(){
        $modeCode = $this->panelArray[14] & 12;
        switch($modeCode){
            case 4:
                return "Filter 1";
                break;
            case 12:
                return "Filter 1 & 2";
                break;
            case 8:
                return "Filter 2";
                break;
            case 0:
            default:
                return "off";
                break;
        }
    }

    /**
     * function is not defined
     * @return string
     */
    public function getAccessibilityType(){
        $modeCode = $this->panelArray[14] & 48;
        switch($modeCode){
            case 16:
                return "Pump light";
                break;
            case 32:
            case 42:
                return "None";
                break;
            case 0:
            default:
                return "All";
                break;
        }
    }

    /**
     * Time scale on 24 hous or 12 hour scale
     * @return bool
     */
    public function is24TimeScale(){
        if (($this->panelArray[14] & 2) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Is lights on of first circuit
     * @return bool
     */
    public function isFirstLightOn(){
        if (($this->panelArray[19] & 3) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Is Second on of first circuit
     * @return bool
     */
    public function isSecondLightOn(){
        if (($this->panelArray[12] & 12) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get heating mode
     * @return string
     */
    public function getHeatingMode(){
        switch($this->panelArray[10]){
            case 0:
                return "Ready";
                break;
            case 1:
                return "Rest";
                break;
            case 2:
                return "ReadyInRest";
                break;
            default:
                return "None";
                break;
        }
    }

    /**
     * @return string
     */
    public function getPumpState1(){
        $byte15 = $this->panelArray[15] & 48;
        $byte16 = $this->panelArray[16] & 3;

        var_dump($byte15);
        var_dump($byte16);

        if($byte16 == 1){
            if($byte15 == 0){  //OR device has pomp 0
                return "low";
            } else {
                return "low heat";
            }
        } elseif($byte16 != 2){
            return "off";
        } elseif ($byte15 == 0){
            return "high";
        }
        return "high heat";
    }

    /**
     * @return string
     */
    public function getPumpState2(){
        switch ($this->panelArray[16] & 12){
            case 4:
                return "low";
                break;
            case 8:
                return "high";
                break;
            default:
                return "off";
                break;
        }
    }

    /**
     * @return string
     */
    public function getPumpState3(){
        switch ($this->panelArray[16] & 48){
            case 16:
                return "low";
                break;
            case 32:
                return "high";
                break;
            default:
                return "off";
                break;
        }
    }

    /**
     * @return string
     */
    public function getPumpState4(){
        switch ($this->panelArray[16] & 192){
            case 64:
                return "low";
                break;
            case 128:
                return "high";
                break;
            default:
                return "off";
                break;
        }
    }

    /**
     * @return string
     */
    public function getPumpState5(){
        switch ($this->panelArray[17] & 3){
            case 1:
                return "low";
                break;
            case 2:
                return "high";
                break;
            default:
                return "off";
                break;
        }
    }

    /**
     * @return string
     */
    public function getPumpState6(){
        switch ($this->panelArray[17] & 12){
            case 4:
                return "low";
                break;
            case 8:
                return "high";
                break;
            default:
                return "off";
                break;
        }
    }

    /**
     * @return bool
     */
    public function isMisterOn(){
        if (($this->panelArray[20] & 1) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isAux1On(){
        if (($this->panelArray[20] & 8) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function isAux2On(){
        if (($this->panelArray[20] & 16) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function getPumpStateStatus(){
        $byte152 = $this->panelArray[15] & 48;
        $byte10 = $this->panelArray[18] & 3;

        if ($this->panelArray[16] < 1 && $this->panelArray[17] < 1 && $byte10 < 1) {
            return "off";
        } elseif ($byte152 == 0 ){ //OR device has pomp 0
            return "low";
        } else {
            return "low heat";
        }
    }

    /**
     * @return string
     */
    public function getWifiState(){
        switch ($this->panelArray[27] & 240){
            case 0:
                return "ok";
                break;
            case 16:
                return "not communicating";
                break;
            case 32:
                return "startup";
                break;
            case 48:
                return "prime";
                break;
            case 64:
                return "hold";
                break;
            case 80:
                return "panel";
                break;
        }
    }
}