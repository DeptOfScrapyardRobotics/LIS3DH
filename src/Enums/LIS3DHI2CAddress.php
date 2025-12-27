<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums;

enum LIS3DHI2CAddress: int
{
    case SDO_GROUNDED = 0x18;
    case SDO_ENERGIZED = 0x19;
}
