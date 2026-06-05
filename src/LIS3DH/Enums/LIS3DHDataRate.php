<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * Output data rate — stored in CTRL_REG1 bits [7:4] (ODR[3:0]).
 *
 * 1.620 kHZ and 5.376 kHZ are only available in low-power mode (LPen=1).
 * All other rates apply to both normal and low-power modes.
 */
enum LIS3DHDataRate: int
{
    case POWER_DOWN = 0x00;
    case HZ1 = 0x01;
    case HZ10 = 0x02;
    case HZ25 = 0x03;
    case HZ50 = 0x04;
    case HZ100 = 0x05;
    case HZ200 = 0x06;
    case HZ400 = 0x07;
    case LOW_POWER_HZ1620 = 0x08;
    case HZ1344_OR_LOW_POWER_HZ5376 = 0x09;
    case RESERVED_1 = 0x0A;
    case RESERVED_2 = 0x0B;
    case RESERVED_3 = 0x0C;
    case RESERVED_4 = 0x0D;
    case RESERVED_5 = 0x0E;
    case RESERVED_6 = 0x0F;

    public function hz(): float
    {
        return match ($this) {
            self::POWER_DOWN => 0.0,
            self::HZ1 => 1.0,
            self::HZ10 => 10.0,
            self::HZ25 => 25.0,
            self::HZ50 => 50.0,
            self::HZ100 => 100.0,
            self::HZ200 => 200.0,
            self::HZ400 => 400.0,
            self::HZ1344_OR_LOW_POWER_HZ5376 => 1344.0,
            self::LOW_POWER_HZ1620 => 1620.0,
        };
    }

    public function toBits(): string
    {
        return match ($this) {
            self::POWER_DOWN => '0000',
            self::HZ1 => '0001',
            self::HZ10 => '0010',
            self::HZ25 => '0011',
            self::HZ50 => '0100',
            self::HZ100 => '0101',
            self::HZ200 => '0110',
            self::HZ400 => '0111',
            self::HZ1344_OR_LOW_POWER_HZ5376 => '1000',
            self::LOW_POWER_HZ1620 => '1001',
            self::RESERVED_1 => '1010',
            self::RESERVED_2 => '1011',
            self::RESERVED_3 => '1100',
            self::RESERVED_4 => '1101',
            self::RESERVED_5 => '1110',
            self::RESERVED_6 => '1111',
        };
    }
}
