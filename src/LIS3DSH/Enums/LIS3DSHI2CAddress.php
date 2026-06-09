<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

/**
 * I2C slave addresses for the LIS3DSH.
 *
 * The SA0/SDO pin selects the LSB of the address:
 *   SA0 tied to GND → 0x1C
 *   SA0 tied to VCC → 0x1D (default on most breakouts)
 */
enum LIS3DSHI2CAddress: int
{
    case SDO_GROUNDED = 0x1C;
    case SDO_ENERGIZED = 0x1D;
}
