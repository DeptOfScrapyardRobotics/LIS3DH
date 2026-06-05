<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Adapters;

use Waveforms\Carriers\I2C\I2CDevice;

class LIS3DHI2CAdapter extends LIS3DHDataCarrier
{
    public function __construct(
        I2CDevice $carrier
    ) {
        parent::__construct($carrier);
    }

    public function read(int $register_hex, int $length): array
    {
        $reg_addr = ($length > 1) ? ($register_hex | 0x80) : ($register_hex & 0x7F);

        /** @var I2CDevice $carrier */
        $carrier = &$this->carrier;

        return $carrier->readWrite([$reg_addr], $length);
    }

    public function write(int $register_hex, array $command_data = []): int
    {
        $payload = [$register_hex & 0xFF, ...$command_data];

        return $this->carrier->write($payload);
    }
}
