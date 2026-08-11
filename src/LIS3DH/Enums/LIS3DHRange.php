<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * Full-scale selection — CTRL4 bits FS1:FS0.
 */
enum LIS3DHRange: int
{
    case G2 = 0b00;
    case G4 = 0b01;
    case G8 = 0b10;
    case G16 = 0b11;

    /**
     * Adafruit base lsb_value before mode adjustment (mg/LSB at 10-bit / normal).
     */
    public function baseLsbMg(): int
    {
        return match ($this) {
            self::G2 => 4,
            self::G4 => 8,
            self::G8 => 16,
            self::G16 => 48,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::G2 => '±2g',
            self::G4 => '±4g',
            self::G8 => '±8g',
            self::G16 => '±16g',
        };
    }
}
