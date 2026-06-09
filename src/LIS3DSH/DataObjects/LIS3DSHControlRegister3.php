<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * CTRL_REG3 (23h) — interrupt control.
 *
 * Layout: DR_EN | IEA | IEL | INT2_EN | INT1_EN | VFILT | - | STRT
 */
readonly class LIS3DSHControlRegister3 extends DataRegister
{
    /**
     * @param  bool  $data_ready_on_int1  DR_EN — routes the DRDY signal to INT1.
     * @param  bool  $interrupts_active_high  IEA — false: active LOW; true: active HIGH.
     * @param  bool  $interrupts_pulsed  IEL — false: latched; true: pulsed.
     * @param  bool  $int2_enabled  INT2_EN — enables the INT2 signal.
     * @param  bool  $int1_enabled  INT1_EN — enables the INT1/DRDY signal.
     * @param  bool  $vector_filter_enabled  VFILT — enables the vector filter.
     * @param  bool  $soft_reset  STRT — triggers a soft reset (POR) when set.
     */
    public function __construct(
        public bool $data_ready_on_int1 = false,
        public bool $interrupts_active_high = false,
        public bool $interrupts_pulsed = false,
        public bool $int2_enabled = false,
        public bool $int1_enabled = false,
        public bool $vector_filter_enabled = false,
        public bool $soft_reset = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->data_ready_on_int1 ? '1' : '0';
        $bit6 = $this->interrupts_active_high ? '1' : '0';
        $bit5 = $this->interrupts_pulsed ? '1' : '0';
        $bit4 = $this->int2_enabled ? '1' : '0';
        $bit3 = $this->int1_enabled ? '1' : '0';
        $bit2 = $this->vector_filter_enabled ? '1' : '0';
        $bit1 = '0';
        $bit0 = $this->soft_reset ? '1' : '0';

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
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            false,
            false,
            false,
            false,
            false,
            false,
            false,
        );
    }
}
