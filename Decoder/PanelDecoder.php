<?php

namespace jpvdw\Balboa\Decoder;

use jpvdw\Balboa\Decoder\BaseDecoder;

class PanelDecoder extends BaseDecoder {

    protected $panelArray = [];
    
    public function __construct($message)
    {
        parent::__construct($message);
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

    public function getPumpStateOne(){

    }




    ///
    /// int tByte15 = byte15 & 48;
    //        byte byte16 = packet[16];
    //        byte b3 = byte16 & 3;
    //        if (b3 == 1) {
    //            if (tByte15 == 0 || SpaController.public) {
    //                controlState.Pump1State = PumpState.Low;
    //            } else {
    //                controlState.Pump1State = PumpState.LowHeat;
    //            }
    //        } else if (b3 != 2) {
    //            controlState.Pump1State = PumpState.Off;
    //        } else if (tByte15 == 0 || SpaController.HasPump0) {
    //            controlState.Pump1State = PumpState.High;
    //        } else {
    //            controlState.Pump1State = PumpState.HighHeat;
    //        }
    //        byte b4 = byte16 & 12;
    //        if (b4 == 4) {
    //            controlState.Pump2State = PumpState.Low;
    //        } else if (b4 == 8) {
    //            controlState.Pump2State = PumpState.High;
    //        } else {
    //            controlState.Pump2State = PumpState.Off;
    //        }
    //        byte b5 = byte16 & 48;
    //        if (b5 == 16) {
    //            controlState.Pump3State = PumpState.Low;
    //        } else if (b5 == 32) {
    //            controlState.Pump3State = PumpState.High;
    //        } else {
    //            controlState.Pump3State = PumpState.Off;
    //        }
    //        byte b6 = byte16 & 192;
    //        if (b6 == 64) {
    //            controlState.Pump4State = PumpState.Low;
    //        } else if (b6 == 128) {
    //            controlState.Pump4State = PumpState.High;
    //        } else {
    //            controlState.Pump4State = PumpState.Off;
    //        }
    //        byte byte17 = packet[17];
    //        byte b7 = byte17 & 3;
    //        if (b7 == 1) {
    //            controlState.Pump5State = PumpState.Low;
    //        } else if (b7 == 2) {
    //            controlState.Pump5State = PumpState.High;
    //        } else {
    //            controlState.Pump5State = PumpState.Off;
    //        }
    //        byte b8 = byte17 & 12;
    //        if (b8 == 4) {
    //            controlState.Pump6State = PumpState.Low;
    //        } else if (b8 == 8) {
    //            controlState.Pump6State = PumpState.High;
    //        } else {
    //            controlState.Pump6State = PumpState.Off;
    //        }


}