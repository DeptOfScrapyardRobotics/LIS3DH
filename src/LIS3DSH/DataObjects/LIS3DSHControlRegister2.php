<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * CTRL_REG2 (22h) — state machine 2 (SM2) configuration.
 *
 * Layout: HYST2_2 HYST1_2 HYST0_2 | - | SM2_PIN | - | - | SM2_EN
 */
readonly class LIS3DSHControlRegister2 extends DataRegister
{
    /**
     * @param  int  $hysteresis  Unsigned 3-bit hysteresis added/subtracted from the SM2 threshold (0–7).
     * @param  bool  $routed_to_int2  false: SM2 interrupt routed to INT1; true: routed to INT2.
     * @param  bool  $state_machine_enabled  Enables state machine 2.
     */
    public function __construct(
        public int $hysteresis = 0,
        public bool $routed_to_int2 = false,
        public bool $state_machine_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bits765 = sprintf('%03b', $this->hysteresis & 0x07);
        $bit4 = '0';
        $bit3 = $this->routed_to_int2 ? '1' : '0';
        $bits21 = '00';
        $bit0 = $this->state_machine_enabled ? '1' : '0';

        return "{$bits765}{$bit4}{$bit3}{$bits21}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            bindec("{$bits[7]}{$bits[6]}{$bits[5]}"),
            (bool) $bits[3],
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(
            0,
            false,
            false,
        );
    }
}
