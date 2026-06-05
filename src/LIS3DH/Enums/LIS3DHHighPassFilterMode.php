<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * High-pass filter mode — stored in CTRL_REG2 bits [7:6] (HPM[1:0]).
 */
enum LIS3DHHighPassFilterMode: int
{
    case NORMAL_MODE_RESET_BY_READING_REFERENCE = 0x00;
    case REFERENCE_SIGNAL_FOR_FILTERING = 0x01;
    case NORMAL_MODE = 0x02;
    case AUTORESET_ON_INTERRUPT_EVENT = 0x03;

    public function toBits(): string
    {
        return match ($this) {
            self::NORMAL_MODE_RESET_BY_READING_REFERENCE => '00',
            self::REFERENCE_SIGNAL_FOR_FILTERING => '01',
            self::NORMAL_MODE => '10',
            self::AUTORESET_ON_INTERRUPT_EVENT => '11',
        };
    }
}
