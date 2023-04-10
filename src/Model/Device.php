<?php

namespace Jpvdw\Balboa\Model;

use Jpvdw\Balboa\Helper\Decoder;

class Device
{
    private array $deviceArray;

    public function __construct($message)
    {
        $this->deviceArray = Decoder::decode($message);
    }

    public function hasPump0(): bool
    {
        return (($this->deviceArray[8] & 128) !== 0);
    }

    public function hasPump1(): bool
    {
        return (($this->deviceArray[5] & 3) !== 0);
    }

    public function hasPump2(): bool
    {
        return (($this->deviceArray[5] & 12) !== 0);
    }

    /**
     * @return bool
     */
    public function hasPump3(): bool
    {
        return (($this->deviceArray[5] & 48) !== 0);
    }

    /**
     * @return bool
     */
    public function hasPump4(): bool
    {
        return (($this->deviceArray[5] & 192) !== 0);
    }

    public function hasPump5(): bool
    {
        return (($this->deviceArray[6] & 3) !== 0);
    }

    public function hasPump6(): bool
    {
        return (($this->deviceArray[6] & 192) !== 0);
    }

    public function hasLight1(): bool
    {
        return (($this->deviceArray[7] & 3) !== 0);
    }

    public function hasLight2(): bool
    {
        return (($this->deviceArray[7] & 192) !== 0);
    }

    public function hasAux1(): bool
    {
        return (($this->deviceArray[9] & 1) !== 0);
    }

    public function hasAux2(): bool
    {
        return (($this->deviceArray[9] & 2) !== 0);
    }

    public function hasBlower(): bool
    {
        return (($this->deviceArray[8] & 0) !== 0);
    }

    public function hasMister(): bool
    {
        return (($this->deviceArray[9] & 16) !== 0);
    }

}