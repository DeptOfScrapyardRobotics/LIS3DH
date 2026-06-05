<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * High-pass filter cutoff selector — stored in CTRL_REG2 bits [5:4] (HPCF[2:1]).
 */
enum LIS3DHHighPassCutoff: int
{
    case CUTOFF_00 = 0x00;
    case CUTOFF_01 = 0x01;
    case CUTOFF_10 = 0x02;
    case CUTOFF_11 = 0x03;

    public function toBits(): string
    {
        return match ($this) {
            self::CUTOFF_00 => '00',
            self::CUTOFF_01 => '01',
            self::CUTOFF_10 => '10',
            self::CUTOFF_11 => '11',
        };
    }
}
