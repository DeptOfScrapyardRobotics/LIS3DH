<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;

readonly class LIS3DHControlRegister1 extends DataRegister
{
    public function __construct(
        public LIS3DHDataRate $data_rate = LIS3DHDataRate::POWER_DOWN,
        public bool $low_power_mode = false,
        public bool $z_axis_enabled = true,
        public bool $y_axis_enabled = true,
        public bool $x_axis_enabled = true,
    ) {}

    public function toBits(): string
    {
        $bits7654 = $this->data_rate->toBits();
        $bit3 = $this->low_power_mode ? '1' : '0';
        $bit2 = $this->z_axis_enabled ? '1' : '0';
        $bit1 = $this->y_axis_enabled ? '1' : '0';
        $bit0 = $this->x_axis_enabled ? '1' : '0';

        return "{$bits7654}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        $bits7654 = bindec("{$bits[7]}{$bits[6]}{$bits[5]}{$bits[4]}");

        return new static(
            LIS3DHDataRate::from($bits7654),
            $bits[3],
            $bits[2],
            $bits[1],
            $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            LIS3DHDataRate::POWER_DOWN,
            false,
            false,
            false,
            false,
        );
    }
}
