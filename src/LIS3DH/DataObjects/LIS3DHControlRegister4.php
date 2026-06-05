<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHSelfTestOption;

readonly class LIS3DHControlRegister4 extends DataRegister
{
    public function __construct(
        public bool $block_data_update = false,
        public bool $big_endian_data_shape = false,
        public LIS3DHRange $range = LIS3DHRange::G2,
        public bool $high_res_output = false,
        public LIS3DHSelfTestOption $self_test_mode = LIS3DHSelfTestOption::DISABLED,
        public bool $spi_3wire_mode = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->block_data_update ? '1' : '0';
        $bit6 = $this->big_endian_data_shape ? '1' : '0';
        $bits54 = $this->range->toBits();
        $bit3 = $this->high_res_output ? '1' : '0';
        $bits21 = $this->self_test_mode->toBits();
        $bit0 = $this->spi_3wire_mode ? '1' : '0';

        return "{$bit7}{$bit6}{$bits54}{$bit3}{$bits21}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);
        $bits54 = bindec("{$bits[5]}{$bits[4]}");
        $bits21 = bindec("{$bits[2]}{$bits[1]}");

        return new static(
            $bits[7],
            $bits[6],
            LIS3DHRange::from($bits54),
            $bits[3],
            LIS3DHSelfTestOption::from($bits21),
            $bits[0]
        );
    }

    public static function none(): static
    {
        return new static(
            false,
            false,
            LIS3DHRange::G2,
            false,
            LIS3DHSelfTestOption::DISABLED,
            false,
        );
    }
}
