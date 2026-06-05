<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * Measurement full-scale range — stored in CTRL_REG4 bits [5:4].
 *
 * In high-resolution mode (HR=1, 12-bit), sensitivity values are as listed.
 * In normal mode (10-bit), sensitivity is 4× larger.
 * In low-power mode (8-bit), sensitivity is 16× larger.
 */
enum LIS3DHSelfTestOption: int
{
    /** ±2 g  — 1 mg/LSB (HR), 4 mg/LSB (Normal), 16 mg/LSB (LP) */
    case DISABLED = 0x00;

    /** ±4 g  — 2 mg/LSB (HR), 8 mg/LSB (Normal), 32 mg/LSB (LP) */
    case NORMAL_SELF_TEST = 0x01;

    /** ±8 g  — 4 mg/LSB (HR), 16 mg/LSB (Normal), 64 mg/LSB (LP) */
    case RESERVED = 0x02;

    /** ±16 g — 12 mg/LSB (HR), 48 mg/LSB (Normal), 192 mg/LSB (LP) */
    case NEGATIVE_SIGN_SELF_TEST = 0x03;

    public function toBits(): string
    {
        return match ($this) {
            self::DISABLED => '00',
            self::NORMAL_SELF_TEST => '01',
            self::RESERVED => '10',
            self::NEGATIVE_SIGN_SELF_TEST => '11',
        };
    }
}
