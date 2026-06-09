<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFifoMode;

/**
 * FIFO_CTRL (2Eh) — FIFO mode and watermark threshold.
 *
 * Layout: FMODE2 FMODE1 FMODE0 | WTMP4 WTMP3 WTMP2 WTMP1 WTMP0
 */
readonly class LIS3DSHFifoControlRegister extends DataRegister
{
    /**
     * @param  int  $watermark  Unsigned 5-bit FIFO watermark pointer / depth (0–31).
     */
    public function __construct(
        public LIS3DSHFifoMode $mode = LIS3DSHFifoMode::BYPASS,
        public int $watermark = 0,
    ) {}

    public function toBits(): string
    {
        $bits765 = $this->mode->toBits();
        $bits43210 = sprintf('%05b', $this->watermark & 0x1F);

        return "{$bits765}{$bits43210}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);
        $bits765 = bindec("{$bits[7]}{$bits[6]}{$bits[5]}");
        $bits43210 = bindec("{$bits[4]}{$bits[3]}{$bits[2]}{$bits[1]}{$bits[0]}");

        return new static(
            LIS3DSHFifoMode::from($bits765),
            $bits43210,
        );
    }

    public static function none(): static
    {
        return new static(
            LIS3DSHFifoMode::BYPASS,
            0,
        );
    }
}
