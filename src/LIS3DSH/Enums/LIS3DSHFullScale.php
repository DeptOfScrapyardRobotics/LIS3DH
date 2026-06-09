<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

/**
 * Full-scale range — stored in CTRL_REG5 bits [5:3] (FSCALE[2:0]).
 *
 * The LIS3DSH outputs 16-bit two's complement right-justified data.
 * Multiply the raw s16le value directly by {@see self::scaleG()} to obtain g.
 */
enum LIS3DSHFullScale: int
{
    /** ±2g  — 0.06 mg/LSB */
    case G2 = 0x00;

    /** ±4g  — 0.12 mg/LSB */
    case G4 = 0x01;

    /** ±6g  — 0.18 mg/LSB */
    case G6 = 0x02;

    /** ±8g  — 0.24 mg/LSB */
    case G8 = 0x03;

    /** ±16g — 0.73 mg/LSB */
    case G16 = 0x04;

    /** Scale factor in g/LSB. */
    public function scaleG(): float
    {
        return match ($this) {
            self::G2 => 0.00006,
            self::G4 => 0.00012,
            self::G6 => 0.00018,
            self::G8 => 0.00024,
            self::G16 => 0.00073,
        };
    }

    /** Human-readable range label. */
    public function label(): string
    {
        return match ($this) {
            self::G2 => '±2g',
            self::G4 => '±4g',
            self::G6 => '±6g',
            self::G8 => '±8g',
            self::G16 => '±16g',
        };
    }

    /** FSCALE[2:0] field — three bits in CTRL_REG5 [5:3]. */
    public function toBits(): string
    {
        return sprintf('%03b', $this->value);
    }
}
