<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHAntiAliasingBandwidth;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFullScale;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHSelfTest;

/**
 * CTRL_REG5 (24h) — anti-aliasing bandwidth, full-scale, self-test, SPI mode.
 *
 * Layout: BW2 BW1 | FSCALE2 FSCALE1 FSCALE0 | ST2 ST1 | SIM
 */
readonly class LIS3DSHControlRegister5 extends DataRegister
{
    public function __construct(
        public LIS3DSHAntiAliasingBandwidth $anti_aliasing_bandwidth = LIS3DSHAntiAliasingBandwidth::HZ800,
        public LIS3DSHFullScale $full_scale = LIS3DSHFullScale::G2,
        public LIS3DSHSelfTest $self_test = LIS3DSHSelfTest::NORMAL,
        public bool $spi_3wire_mode = false,
    ) {}

    public function toBits(): string
    {
        $bits76 = $this->anti_aliasing_bandwidth->toBits();
        $bits543 = $this->full_scale->toBits();
        $bits21 = $this->self_test->toBits();
        $bit0 = $this->spi_3wire_mode ? '1' : '0';

        return "{$bits76}{$bits543}{$bits21}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);
        $bits76 = bindec("{$bits[7]}{$bits[6]}");
        $bits543 = bindec("{$bits[5]}{$bits[4]}{$bits[3]}");
        $bits21 = bindec("{$bits[2]}{$bits[1]}");

        return new static(
            LIS3DSHAntiAliasingBandwidth::from($bits76),
            LIS3DSHFullScale::from($bits543),
            LIS3DSHSelfTest::from($bits21),
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            LIS3DSHAntiAliasingBandwidth::HZ800,
            LIS3DSHFullScale::G2,
            LIS3DSHSelfTest::NORMAL,
            false,
        );
    }
}
