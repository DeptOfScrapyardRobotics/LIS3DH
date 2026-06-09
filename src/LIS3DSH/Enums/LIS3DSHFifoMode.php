<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

/**
 * FIFO mode selection — stored in FIFO_CTRL bits [7:5] (FMODE[2:0]).
 */
enum LIS3DSHFifoMode: int
{
    /** Bypass mode — FIFO turned off */
    case BYPASS = 0x00;

    /** FIFO mode — stops collecting data when FIFO is full */
    case FIFO = 0x01;

    /** Stream mode — when full, the new sample overwrites the older one */
    case STREAM = 0x02;

    /** Stream mode until trigger is de-asserted, then FIFO mode */
    case STREAM_TO_FIFO = 0x03;

    /** Bypass mode until trigger is de-asserted, then Stream mode */
    case BYPASS_TO_STREAM = 0x04;

    /** Bypass mode until trigger is de-asserted, then FIFO mode */
    case BYPASS_TO_FIFO = 0x07;

    /** FMODE[2:0] field — three bits in FIFO_CTRL [7:5]. */
    public function toBits(): string
    {
        return sprintf('%03b', $this->value);
    }
}
