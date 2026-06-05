<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * Measurement full-scale range — stored in CTRL_REG4 bits [5:4].
 *
 * In high-resolution mode (HR=1, 12-bit), sensitivity values are as listed.
 * In normal mode (10-bit), sensitivity is 4× larger.
 * In low-power mode (8-bit), sensitivity is 16× larger.
 */
enum LIS3DHRange: int
{
    /** ±2 g  — 1 mg/LSB (HR), 4 mg/LSB (Normal), 16 mg/LSB (LP) */
    case G2 = 0x00;

    /** ±4 g  — 2 mg/LSB (HR), 8 mg/LSB (Normal), 32 mg/LSB (LP) */
    case G4 = 0x01;

    /** ±8 g  — 4 mg/LSB (HR), 16 mg/LSB (Normal), 64 mg/LSB (LP) */
    case G8 = 0x02;

    /** ±16 g — 12 mg/LSB (HR), 48 mg/LSB (Normal), 192 mg/LSB (LP) */
    case G16 = 0x03;

    /**
     * Scale factor in g/LSB for high-resolution mode (12-bit, HR=1).
     *
     * The main class right-shifts the 16-bit raw word by 4 before multiplying,
     * yielding a signed 12-bit integer, then applies this factor.
     */
    public function scaleHR(): float
    {
        return match ($this) {
            self::G2 => 0.001,
            self::G4 => 0.002,
            self::G8 => 0.004,
            self::G16 => 0.012,
        };
    }

    /**
     * Scale factor in g/LSB for normal mode (10-bit).
     *
     * Right-shift the raw word by 6 before multiplying.
     */
    public function scaleNormal(): float
    {
        return match ($this) {
            self::G2 => 0.004,
            self::G4 => 0.008,
            self::G8 => 0.016,
            self::G16 => 0.048,
        };
    }

    /** Human-readable range label. */
    public function label(): string
    {
        return match ($this) {
            self::G2 => '±2g',
            self::G4 => '±4g',
            self::G8 => '±8g',
            self::G16 => '±16g',
        };
    }

    public function toBits(): string
    {
        return match ($this) {
            self::G2 => '00',
            self::G4 => '01',
            self::G8 => '10',
            self::G16 => '11',
        };
    }
}
