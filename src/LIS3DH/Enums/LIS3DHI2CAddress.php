<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

/**
 * I2C slave addresses for the LIS3DH.
 *
 * The SA0/SDO pin selects the LSB of the address:
 *   SA0 tied to GND → 0x18
 *   SA0 tied to VCC → 0x19 (default on most breakouts)
 */
enum LIS3DHI2CAddress: int
{
    case SDO_GROUNDED = 0x18;
    case SDO_ENERGIZED = 0x19;
}
