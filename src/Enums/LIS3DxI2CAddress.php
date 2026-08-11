<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\Enums;

/**
 * LIS3DH / LIS3DSH 7-bit I2C addresses (SA0 / SDO pin).
 */
enum LIS3DxI2CAddress: int
{
    /** SA0 / SDO grounded — Adafruit default */
    case SA0_GROUNDED = 0x18;

    /** SA0 / SDO pulled high */
    case SA0_ENERGIZED = 0x19;
}
