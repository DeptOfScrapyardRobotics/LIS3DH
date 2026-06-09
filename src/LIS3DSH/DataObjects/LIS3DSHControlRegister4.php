<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOutputDataRate;

/**
 * CTRL_REG4 (20h) — output data rate, block data update and per-axis enable.
 *
 * Layout: ODR3 ODR2 ODR1 ODR0 | BDU | Zen | Yen | Xen
 */
readonly class LIS3DSHControlRegister4 extends DataRegister
{
    public function __construct(
        public LIS3DSHOutputDataRate $output_data_rate = LIS3DSHOutputDataRate::POWER_DOWN,
        public bool $block_data_update = false,
        public bool $z_axis_enabled = true,
        public bool $y_axis_enabled = true,
        public bool $x_axis_enabled = true,
    ) {}

    public function toBits(): string
    {
        $bits7654 = $this->output_data_rate->toBits();
        $bit3 = $this->block_data_update ? '1' : '0';
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
            LIS3DSHOutputDataRate::from($bits7654),
            (bool) $bits[3],
            (bool) $bits[2],
            (bool) $bits[1],
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            LIS3DSHOutputDataRate::POWER_DOWN,
            false,
            false,
            false,
            false,
        );
    }
}
