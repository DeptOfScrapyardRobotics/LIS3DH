<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

enum LIS3DHReadRegister: int
{
    case DATA_FROM_ADC1_L_REGISTER = 0x08;
    case DATA_FROM_ADC2_L_REGISTER = 0x0A;
    case DATA_FROM_ADC3_L_REGISTER = 0x0C;
    // Device identification — WHO_AM_I (0x0F) returns 0x33
    case WHO_AM_I_REGISTER = 0x0F;
    case TEMP_CONFIG_REGISTER = 0x1F;

    // Control registers
    case CTRL_REGISTER1 = 0x20;
    case CTRL_REGISTER2 = 0x21;
    case CTRL_REGISTER3 = 0x22;
    case CTRL_REGISTER4 = 0x23;
    case CTRL_REGISTER5 = 0x24;
    case CTRL_REGISTER6 = 0x25;

    // Acceleration output (little-endian pairs)
    case DATA_FROM_X_L_REGISTER = 0x28;
    case DATA_FROM_X_H_REGISTER = 0x29;
    case DATA_FROM_Y_L_REGISTER = 0x2A;
    case DATA_FROM_Y_H_REGISTER = 0x2B;
    case DATA_FROM_Z_L_REGISTER = 0x2C;
    case DATA_FROM_Z_H_REGISTER = 0x2D;

    case CLICK_SOURCE_REGISTER = 0x39;
}
