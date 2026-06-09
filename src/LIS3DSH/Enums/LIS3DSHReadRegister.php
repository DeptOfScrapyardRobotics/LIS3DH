<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

enum LIS3DSHReadRegister: int
{
    /** Temperature output — 8-bit two's complement, 1 LSB/°C */
    case REG_OUT_T = 0x0C;

    case REG_INFO1 = 0x0D;
    case REG_INFO2 = 0x0E;

    /** Device identification register — expected value 0x3F */
    case REG_WHO_AM_I = 0x0F;

    // Control registers (note the LIS3DSH ordering: CTRL_REG4 sits at 0x20)
    case REG_CTRL_REG4 = 0x20;
    case REG_CTRL_REG1 = 0x21;
    case REG_CTRL_REG2 = 0x22;
    case REG_CTRL_REG3 = 0x23;
    case REG_CTRL_REG5 = 0x24;
    case REG_CTRL_REG6 = 0x25;

    /** Status data register */
    case REG_STATUS = 0x27;

    // Acceleration output (little-endian pairs)
    case REG_OUT_X_L = 0x28;
    case REG_OUT_X_H = 0x29;
    case REG_OUT_Y_L = 0x2A;
    case REG_OUT_Y_H = 0x2B;
    case REG_OUT_Z_L = 0x2C;
    case REG_OUT_Z_H = 0x2D;

    case REG_FIFO_CTRL = 0x2E;
    case REG_FIFO_SRC = 0x2F;
}
