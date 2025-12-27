<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums;

enum LIS3DHCommand: int
{
    case AUX_STATUS = 0x07;

    /**< 1-axis acceleration data. Low value */
    case ADC1_OUT_LOW = 0x08;
    /**< 1-axis acceleration data. High value */
    case ADC1_OUT_HIGH = 0x09;
    /**< 2-axis acceleration data. Low value */
    case ADC2_OUT_LOW = 0x0A;
    /**< 2-axis acceleration data. High value */
    case ADC2_OUT_HIGH = 0x0B;
    /**< 3-axis acceleration data. Low value */
    case ADC3_OUT_LOW = 0x0C;
    /**< 3-axis acceleration data. High value */
    case ADC3_OUT_HIGH = 0x0D;
    /**< INT_COUNTER register [IC7, IC6, IC5, IC4, IC3, IC2, IC1, IC0] */
    case INT_COUNTER = 0x0E;
    /**< Device identification register. [0, 0, 1, 1, 0, 0, 1, 1] */
    case WHO_AM_I = 0x0F;

    /**
     *  TEMP_CFG_REG
     *  Temperature configuration register.
     *   ADC_PD   ADC enable. Default value: 0
     *            (0: ADC disabled; 1: ADC enabled)
     *   TEMP_EN  Temperature sensor (T) enable. Default value: 0
     *            (0: T disabled; 1: T enabled)
     */
    case TEMP_CONFIG_REGISTER = 0x1F;

    case CONTROL_REGISTER_1 = 0x20;
    case CONTROL_REGISTER_2 = 0x21;
    case CONTROL_REGISTER_3 = 0x22;
    case CONTROL_REGISTER_4 = 0x23;
    case CONTROL_REGISTER_5 = 0x24;
    case CONTROL_REGISTER_6 = 0x25;
    case REFERENCE_REGISTER = 0x26;
    case PRIMARY_STATUS_REGISTER = 0x27;
    case X_AXIS_LOW = 0x28;
    case X_AXIS_HIGH = 0x29;
    case Y_AXIS_LOW = 0x2A;
    case Y_AXIS_HIGH = 0x2B;
    case Z_AXIS_LOW = 0x2C;
    case Z_AXIS_HIGH = 0x2D;
    case FIFO_CONTROL_REGISTER = 0x2E;
    case FIFO_SOURCE_REGISTER = 0x2F;

    case INT1_CONFIG_REGISTER = 0x30;
    case INT1_SOURCE_REGISTER = 0x31;
    case INT1_THS_REGISTER = 0x32;
    case INT1_DURATION_REGISTER = 0x33;

    case CLICK_CONFIG_REGISTER = 0x38;
    case CLICK_SOURCE_REGISTER = 0x39;
    case CLICK_THS_REGISTER = 0x3A;

    case TIME_LIMIT_REGISTER = 0x3B;
    case TIME_LAG_REGISTER = 0x3C;
    case TIME_WINDOW_REGISTER = 0x3D;

    case AUTO_INC_FROM_X_AXIS = 0xA8; //0x28 | 0x80;
    case AUTO_INC_FROM_Y_AXIS = 0xAA; //2A | 0x80;
    case AUTO_INC_FROM_Z_AXIS = 0xAC; //2C | 0x80;
}
