<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

/**
 * Output data rate — stored in CTRL_REG4 bits [7:4] (ODR[3:0]).
 */
enum LIS3DSHOutputDataRate: int
{
    /** Power-down — sensor disabled */
    case POWER_DOWN = 0x00;

    /** 3.125 Hz */
    case HZ3_125 = 0x01;

    /** 6.25 Hz */
    case HZ6_25 = 0x02;

    /** 12.5 Hz */
    case HZ12_5 = 0x03;

    /** 25 Hz */
    case HZ25 = 0x04;

    /** 50 Hz */
    case HZ50 = 0x05;

    /** 100 Hz — default */
    case HZ100 = 0x06;

    /** 400 Hz */
    case HZ400 = 0x07;

    /** 800 Hz */
    case HZ800 = 0x08;

    /** 1600 Hz */
    case HZ1600 = 0x09;

    /** Nominal output data rate in Hz. */
    public function hz(): float
    {
        return match ($this) {
            self::POWER_DOWN => 0.0,
            self::HZ3_125 => 3.125,
            self::HZ6_25 => 6.25,
            self::HZ12_5 => 12.5,
            self::HZ25 => 25.0,
            self::HZ50 => 50.0,
            self::HZ100 => 100.0,
            self::HZ400 => 400.0,
            self::HZ800 => 800.0,
            self::HZ1600 => 1600.0,
        };
    }

    /** ODR[3:0] field — four bits in CTRL_REG4 [7:4]. */
    public function toBits(): string
    {
        return sprintf('%04b', $this->value);
    }
}
