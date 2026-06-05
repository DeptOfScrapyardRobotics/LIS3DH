<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;

readonly class LIS3DHTempConfigRegister extends DataRegister
{
    public function __construct(
        public bool $adc_pinout_enabled = false,
        public bool $internal_temp_sensor_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->adc_pinout_enabled ? '1' : '0';
        $bit6 = $this->internal_temp_sensor_enabled ? '1' : '0';
        $bits543210 = '000000';

        return "{$bit7}{$bit6}{$bits543210}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            $bits[7],
            $bits[6],
        );
    }

    public static function none(): static
    {
        return new static(
            false,
            false,
        );
    }
}
