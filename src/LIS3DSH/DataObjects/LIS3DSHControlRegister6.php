<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * CTRL_REG6 (25h) — reboot, FIFO control and INT1/INT2 FIFO routing.
 *
 * Layout: BOOT | FIFO_EN | WTM_EN | ADD_INC | P1_EMPTY | P1_WTM | P1_OVERRUN | P2_BOOT
 *
 * ADD_INC defaults to enabled (register value 0x10) and must stay enabled for
 * burst reads of the output registers to auto-increment the sub-address.
 */
readonly class LIS3DSHControlRegister6 extends DataRegister
{
    /**
     * @param  bool  $reboot  BOOT — forces a reboot of trimming parameters; self-clears.
     * @param  bool  $fifo_enabled  FIFO_EN — enables the FIFO buffer.
     * @param  bool  $watermark_enabled  WTM_EN — enables FIFO watermark level use.
     * @param  bool  $address_auto_increment  ADD_INC — auto-increment sub-address on multi-byte access.
     * @param  bool  $int1_fifo_empty_enabled  P1_EMPTY — routes FIFO empty indication to INT1.
     * @param  bool  $int1_fifo_watermark_enabled  P1_WTM — routes FIFO watermark interrupt to INT1.
     * @param  bool  $int1_fifo_overrun_enabled  P1_OVERRUN — routes FIFO overrun interrupt to INT1.
     * @param  bool  $int2_boot_enabled  P2_BOOT — routes the BOOT interrupt to INT2.
     */
    public function __construct(
        public bool $reboot = false,
        public bool $fifo_enabled = false,
        public bool $watermark_enabled = false,
        public bool $address_auto_increment = true,
        public bool $int1_fifo_empty_enabled = false,
        public bool $int1_fifo_watermark_enabled = false,
        public bool $int1_fifo_overrun_enabled = false,
        public bool $int2_boot_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->reboot ? '1' : '0';
        $bit6 = $this->fifo_enabled ? '1' : '0';
        $bit5 = $this->watermark_enabled ? '1' : '0';
        $bit4 = $this->address_auto_increment ? '1' : '0';
        $bit3 = $this->int1_fifo_empty_enabled ? '1' : '0';
        $bit2 = $this->int1_fifo_watermark_enabled ? '1' : '0';
        $bit1 = $this->int1_fifo_overrun_enabled ? '1' : '0';
        $bit0 = $this->int2_boot_enabled ? '1' : '0';

        return "{$bit7}{$bit6}{$bit5}{$bit4}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            (bool) $bits[7],
            (bool) $bits[6],
            (bool) $bits[5],
            (bool) $bits[4],
            (bool) $bits[3],
            (bool) $bits[2],
            (bool) $bits[1],
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            false,
            false,
            false,
            true,
            false,
            false,
            false,
            false,
        );
    }
}
