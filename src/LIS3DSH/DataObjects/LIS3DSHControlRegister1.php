<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * CTRL_REG1 (21h) — state machine 1 (SM1) configuration.
 *
 * Layout: HYST2_1 HYST1_1 HYST0_1 | - | SM1_PIN | - | - | SM1_EN
 */
readonly class LIS3DSHControlRegister1 extends DataRegister
{
    /**
     * @param  int  $hysteresis  Unsigned 3-bit hysteresis added/subtracted from the SM1 threshold (0–7).
     * @param  bool  $routed_to_int2  false: SM1 interrupt routed to INT1; true: routed to INT2.
     * @param  bool  $state_machine_enabled  Enables state machine 1.
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
