<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums;

enum LIS3DSHOpCode: int
{
    /** ODR[3:0] @ [7:4], BDU @ [3], Zen/Yen/Xen @ [2:0] */
    case REG_CTRL_REG4 = 0x20;

    /** HYST1[2:0] @ [7:5], SM1_PIN @ [3], SM1_EN @ [0] */
    case REG_CTRL_REG1 = 0x21;

    /** HYST2[2:0] @ [7:5], SM2_PIN @ [3], SM2_EN @ [0] */
    case REG_CTRL_REG2 = 0x22;

    /** DR_EN @ [7], IEA @ [6], IEL @ [5], INT2_EN @ [4], INT1_EN @ [3], VFILT @ [2], STRT @ [0] */
    case REG_CTRL_REG3 = 0x23;

    /** BW[1:0] @ [7:6], FSCALE[2:0] @ [5:3], ST[1:0] @ [2:1], SIM @ [0] */
    case REG_CTRL_REG5 = 0x24;

    /** BOOT @ [7], FIFO_EN @ [6], WTM_EN @ [5], ADD_INC @ [4], P1_EMPTY @ [3], P1_WTM @ [2], P1_OVERRUN @ [1], P2_BOOT @ [0] */
    case REG_CTRL_REG6 = 0x25;

    /** FMODE[2:0] @ [7:5], WTMP[4:0] @ [4:0] */
    case REG_FIFO_CTRL = 0x2E;
}
