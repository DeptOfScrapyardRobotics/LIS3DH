<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;

readonly class LIS3DHControlRegister3 extends DataRegister
{
    public function __construct(
        public bool $int1_tap_interrupt_enabled = false,
        public bool $int1_gen_events_enabled = false,
        public bool $int2_gen_events_enabled = false,
        public bool $int1_data_ready_signal_enabled = false,
        public bool $int2_data_ready_signal_enabled = false,
        public bool $int1_fifo_watermark_enabled = false,
        public bool $int1_fifo_overrun_interrupt_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->int1_tap_interrupt_enabled ? '1' : '0';
        $bit6 = $this->int1_gen_events_enabled ? '1' : '0';
        $bit5 = $this->int2_gen_events_enabled ? '1' : '0';
        $bit4 = $this->int1_data_ready_signal_enabled ? '1' : '0';
        $bit3 = $this->int2_data_ready_signal_enabled ? '1' : '0';
        $bit2 = $this->int1_fifo_watermark_enabled ? '1' : '0';
        $bit1 = $this->int1_fifo_overrun_interrupt_enabled ? '1' : '0';
        $bit0 = '0';

        return "{$bit7}{$bit6}{$bit5}{$bit4}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            $bits[7],
            $bits[6],
            $bits[5],
            $bits[4],
            $bits[3],
            $bits[2],
            $bits[1]
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
