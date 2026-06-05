<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHHighPassCutoff;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHHighPassFilterMode;

readonly class LIS3DHControlRegister2 extends DataRegister
{
    public function __construct(
        public LIS3DHHighPassFilterMode $hp_filter_mode = LIS3DHHighPassFilterMode::NORMAL_MODE_RESET_BY_READING_REFERENCE,
        public LIS3DHHighPassCutoff $hp_cutoff = LIS3DHHighPassCutoff::CUTOFF_00,
        public bool $filtered_data_selection = false,
        public bool $hp_filter_for_click_ints_enabled = false,
        public bool $hp_filter_for_int2_enabled = false,
        public bool $hp_filter_for_int1_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bits76 = $this->hp_filter_mode->toBits();
        $bits54 = $this->hp_cutoff->toBits();
        $bit3 = $this->filtered_data_selection ? '1' : '0';
        $bit2 = $this->hp_filter_for_click_ints_enabled ? '1' : '0';
        $bit1 = $this->hp_filter_for_int2_enabled ? '1' : '0';
        $bit0 = $this->hp_filter_for_int1_enabled ? '1' : '0';

        return "{$bits76}{$bits54}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);
        $bits76 = bindec("{$bits[7]}{$bits[6]}");
        $bits54 = bindec("{$bits[5]}{$bits[4]}");

        return new static(
            LIS3DHHighPassFilterMode::from($bits76),
            LIS3DHHighPassCutoff::from($bits54),
            $bits[3],
            $bits[2],
            $bits[1],
            $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            LIS3DHHighPassFilterMode::NORMAL_MODE_RESET_BY_READING_REFERENCE,
            LIS3DHHighPassCutoff::CUTOFF_00,
            false,
            false,
            false,
            false
        );
    }
}
