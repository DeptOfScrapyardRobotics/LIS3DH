<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * STATUS (27h) — read-only data-availability and overrun flags.
 *
 * Layout: ZYXOR | ZOR | YOR | XOR | ZYXDA | ZDA | YDA | XDA
 */
readonly class LIS3DSHStatusRegister extends DataRegister
{
    public function __construct(
        public bool $xyz_overrun = false,
        public bool $z_overrun = false,
        public bool $y_overrun = false,
        public bool $x_overrun = false,
        public bool $xyz_data_available = false,
        public bool $z_data_available = false,
        public bool $y_data_available = false,
        public bool $x_data_available = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->xyz_overrun ? '1' : '0';
        $bit6 = $this->z_overrun ? '1' : '0';
        $bit5 = $this->y_overrun ? '1' : '0';
        $bit4 = $this->x_overrun ? '1' : '0';
        $bit3 = $this->xyz_data_available ? '1' : '0';
        $bit2 = $this->z_data_available ? '1' : '0';
        $bit1 = $this->y_data_available ? '1' : '0';
        $bit0 = $this->x_data_available ? '1' : '0';

        return "{$bit7}{$bit6}{$bit5}{$bit4}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            (bool) $bits[7],
            (bool) $bits[6],
            (bool) $bits[5],
            (bool) $bits[4],
            (bool) $bits[3],
            (bool) $bits[2],
            (bool) $bits[1],
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
        );
    }
}
