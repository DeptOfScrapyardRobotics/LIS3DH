<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;

readonly class LIS3DHControlRegister5 extends DataRegister
{
    public function __construct(
        public bool $reboot_memory_content = false,
        public bool $fifo_buffer_enabled = false,
        public bool $latch_int1 = false,
        public bool $dir_4D_detection_on_int1_enabled = false,
        public bool $ints_active_high = false,
        public bool $dir_4D_detection_on_int2_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->reboot_memory_content ? '1' : '0';
        $bit6 = $this->fifo_buffer_enabled ? '1' : '0';
        $bits54 = '00';
        $bit3 = $this->latch_int1 ? '1' : '0';
        $bit2 = $this->dir_4D_detection_on_int1_enabled ? '1' : '0';
        $bit1 = $this->ints_active_high ? '1' : '0';
        $bit0 = $this->dir_4D_detection_on_int2_enabled ? '1' : '0';

        return "{$bit7}{$bit6}{$bits54}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            $bits[7],
            $bits[6],
            $bits[3],
            $bits[2],
            $bits[1],
            $bits[0],
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
        );
    }
}
