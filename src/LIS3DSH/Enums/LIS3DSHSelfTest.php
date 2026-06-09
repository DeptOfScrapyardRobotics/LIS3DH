<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

/**
 * Self-test mode — stored in CTRL_REG5 bits [2:1] (ST[2:1]).
 */
enum LIS3DSHSelfTest: int
{
    /** Self-test disabled (normal operating mode) */
    case NORMAL = 0x00;

    /** Positive sign self-test */
    case POSITIVE = 0x01;

    /** Negative sign self-test */
    case NEGATIVE = 0x02;

    /** Reserved — must not be programmed */
    case NOT_ALLOWED = 0x03;

    /** ST[2:1] field — two bits in CTRL_REG5 [2:1]. */
    public function toBits(): string
    {
        return sprintf('%02b', $this->value);
    }
}
