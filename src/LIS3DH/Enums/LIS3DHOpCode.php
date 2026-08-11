<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

enum LIS3DHOpCode: int
{
    case WHO_AM_I = 0x0F;
    case TEMP_CFG = 0x1F;
    case CTRL1 = 0x20;
    case CTRL2 = 0x21;
    case CTRL3 = 0x22;
    case CTRL4 = 0x23;
    case CTRL5 = 0x24;
    case CTRL6 = 0x25;
    case STATUS = 0x27;
    case OUT_X_L = 0x28;
    case OUT_X_H = 0x29;
    case OUT_Y_L = 0x2A;
    case OUT_Y_H = 0x2B;
    case OUT_Z_L = 0x2C;
    case OUT_Z_H = 0x2D;
}
