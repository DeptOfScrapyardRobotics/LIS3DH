<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * Performance mode — combination of CTRL1 LPen and CTRL4 HR.
 */
enum LIS3DHMode: int
{
    case LOW_POWER = 0x0;
    case NORMAL = 0x1;
    case HIGH_RESOLUTION = 0x2;

    /**
     * Adafruit convert_from_LSB16 divisor for this mode.
     */
    public function lsb16Divisor(): float
    {
        return match ($this) {
            self::LOW_POWER => 256000.0,
            self::NORMAL => 64000.0,
            self::HIGH_RESOLUTION => 16000.0,
        };
    }
}
