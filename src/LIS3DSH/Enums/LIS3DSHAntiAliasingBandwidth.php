<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

/**
 * Anti-aliasing filter bandwidth — stored in CTRL_REG5 bits [7:6] (BW[2:1]).
 */
enum LIS3DSHAntiAliasingBandwidth: int
{
    /** 800 Hz — default */
    case HZ800 = 0x00;

    /** 200 Hz */
    case HZ200 = 0x01;

    /** 400 Hz */
    case HZ400 = 0x02;

    /** 50 Hz */
    case HZ50 = 0x03;

    /** Nominal filter bandwidth in Hz. */
    public function hz(): float
    {
        return match ($this) {
            self::HZ800 => 800.0,
            self::HZ200 => 200.0,
            self::HZ400 => 400.0,
            self::HZ50 => 50.0,
        };
    }

    /** BW[2:1] field — two bits in CTRL_REG5 [7:6]. */
    public function toBits(): string
    {
        return sprintf('%02b', $this->value);
    }
}
