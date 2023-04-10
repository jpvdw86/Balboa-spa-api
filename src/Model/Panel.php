<?php

namespace Jpvdw\Balboa\Model;

use Jpvdw\Balboa\Helper\Decoder;

class Panel {

    private array $panelArray;
    
    public function __construct($message)
    {
        $this->panelArray = Decoder::decode($message);

    }

    /**
     * Check of temperature scale is Celsius
     */
    public function isTemperatureScaleCelsius(): bool
    {
        return (($this->panelArray[14] & 1) !== 0);
    }

    /**
     * Get type of heating mode
     */
    public function isHeatingModeHigh(): bool
    {
        return (($this->panelArray[15] & 4) === 0);
    }

    /**
     * Get temperature scale.
     * (Farenheit is not correct in scale. but application use it like this)
     */
    public function getTemperature(): float
    {
        if ($this->panelArray[7] === 255) {
            return (float)$this->panelArray[7];
        }
        return $this->isTemperatureScaleCelsius() ? (float)($this->panelArray[7] / 2) : (float)$this->panelArray[7];
    }

    /**
     * Get Target temperature
     */
    public function getTargetTemperature(): float
    {
        if ($this->panelArray[25] === 255) {
            return (float)$this->panelArray[25];
        }
        return $this->isTemperatureScaleCelsius() ? (float)($this->panelArray[25] / 2) : (float)$this->panelArray[25];
    }

    /**
     * Get filter cycle mode
     */
    public function getFilterMode(): string
    {
        $modeCode = $this->panelArray[14] & 12;
        switch($modeCode){
            case 4:
                return "Filter 1";
            case 12:
                return "Filter 1 & 2";
            case 8:
                return "Filter 2";
            case 0:
            default:
                return "off";
        }
    }

    public function getAccessibilityType(): string
    {
        $modeCode = $this->panelArray[14] & 48;
        switch($modeCode){
            case 16:
                return "Pump light";
            case 32:
            case 42:
                return "None";
            case 0:
            default:
                return "All";
        }
    }

    /**
     * Time scale on 24 hour or 12 hour scale
     */
    public function is24TimeScale(): bool
    {
        return (($this->panelArray[14] & 2) !== 0);
    }

    /**
     * Is lights on of first circuit
     */
    public function isFirstLightOn(): bool
    {
        return (($this->panelArray[19] & 3) !== 0);
    }

    /**
     * Is Second on of first circuit
     * @return bool
     */
    public function isSecondLightOn(): bool
    {
        return (($this->panelArray[12] & 12) !== 0);
    }

    /**
     * Get heating mode
     */
    public function getHeatingMode(): string
    {
        switch($this->panelArray[10]){
            case 0:
                return "Ready";
            case 1:
                return "Rest";
            case 2:
                return "ReadyInRest";
            default:
                return "None";
        }
    }

    public function getPumpState1(): string
    {
        $byte15 = $this->panelArray[15] & 48;
        $byte16 = $this->panelArray[16] & 3;

        if ($byte16 === 1) {
            if($byte15 === 0){  //OR device has pomp 0
                return "low";
            }
            return "low heat";
        }

        if ($byte16 !== 2) {
            return "off";
        }

        if($byte15 === 0) {
            return "high";
        }

        return "high heat";
    }

    public function getPumpState2(): string
    {
        switch ($this->panelArray[16] & 12){
            case 4:
                return "low";
            case 8:
                return "high";
            default:
                return "off";
        }
    }

    public function getPumpState3(): string
    {
        switch ($this->panelArray[16] & 48){
            case 16:
                return "low";
            case 32:
                return "high";
            default:
                return "off";
        }
    }

    public function getPumpState4(): string
    {
        switch ($this->panelArray[16] & 192){
            case 64:
                return "low";
            case 128:
                return "high";
            default:
                return "off";
        }
    }

    public function getPumpState5(): string
    {
        switch ($this->panelArray[17] & 3){
            case 1:
                return "low";
            case 2:
                return "high";
            default:
                return "off";
        }
    }

    /**
     * @return string
     */
    public function getPumpState6(): string
    {
        switch ($this->panelArray[17] & 12){
            case 4:
                return "low";
            case 8:
                return "high";
            default:
                return "off";
        }
    }

    /**
     * @return bool
     */
    public function isMisterOn(): bool
    {
        return (($this->panelArray[20] & 1) !== 0);
    }


    public function isAux1On(): bool
    {
        return (($this->panelArray[20] & 8) !== 0);
    }

    public function isAux2On(): bool
    {
        return (($this->panelArray[20] & 16) !== 0);
    }


    public function getPumpStateStatus(): string
    {
        $byte152 = $this->panelArray[15] & 48;
        $byte10 = $this->panelArray[18] & 3;

        if ($this->panelArray[16] < 1 && $this->panelArray[17] < 1 && $byte10 < 1) {
            return "off";
        }

        if ($byte152 === 0) {
            return "low";
        }

        return "low heat";
    }

    public function getWifiState(): string
    {
        switch ($this->panelArray[27] & 240){
            case 0:
                return "ok";
            default:
            case 16:
                return "not communicating";
            case 32:
                return "startup";
            case 48:
                return "prime";
            case 64:
                return "hold";
            case 80:
                return "panel";
        }
    }
}