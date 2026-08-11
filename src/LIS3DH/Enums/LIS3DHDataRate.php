<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * Output data rate — CTRL1 bits ODR3:ODR0.
 */
enum LIS3DHDataRate: int
{
    case POWERDOWN = 0b0000;
    case HZ1 = 0b0001;
    case HZ10 = 0b0010;
    case HZ25 = 0b0011;
    case HZ50 = 0b0100;
    case HZ100 = 0b0101;
    case HZ200 = 0b0110;
    case HZ400 = 0b0111;
    case LOWPOWER_1K6HZ = 0b1000;
    case LOWPOWER_5KHZ = 0b1001;

    public function hz(): float
    {
        return match ($this) {
            self::POWERDOWN => 0.0,
            self::HZ1 => 1.0,
            self::HZ10 => 10.0,
            self::HZ25 => 25.0,
            self::HZ50 => 50.0,
            self::HZ100 => 100.0,
            self::HZ200 => 200.0,
            self::HZ400 => 400.0,
            self::LOWPOWER_1K6HZ => 1600.0,
            self::LOWPOWER_5KHZ => 5000.0,
        };
    }
}
