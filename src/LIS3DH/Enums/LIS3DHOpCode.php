<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums;

enum LIS3DHOpCode: int
{
    // Control registers
    case TEMP_CONFIG_REGISTER = 0x1F;
    case CTRL_REGISTER1 = 0x20;
    case CTRL_REGISTER2 = 0x21;
    case CTRL_REGISTER3 = 0x22;
    case CTRL_REGISTER4 = 0x23;
    case CTRL_REGISTER5 = 0x24;
    case CTRL_REGISTER6 = 0x25;

    case CLICK_CONFIG_REGISTER = 0x38;
    case CLICK_THRESHOLD_REGISTER = 0x3A;
    case TIME_LIMIT_REGISTER = 0x3B;
    case TIME_LATENCY_REGISTER = 0x3C;
    case TIME_WINDOW_REGISTER = 0x3D;
}
