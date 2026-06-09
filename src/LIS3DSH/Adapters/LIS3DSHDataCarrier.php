<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Adapters;

use Waveforms\Carriers\I2C\I2CDevice;
use Waveforms\Carriers\SPI\SPIDevice;

abstract class LIS3DSHDataCarrier
{
    public function __construct(
        protected I2CDevice|SPIDevice $carrier
    ) {}

    abstract public function read(int $register_hex, int $length): array;

    abstract public function write(int $register_hex, array $command_data = []): int;
}
