<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects;

use BareMetal\DataObjects\DataRegister;

readonly class LIS3DHControlRegister6 extends DataRegister
{
    public function __construct(
        public bool $int2_tap_interrupt_enabled = false,
        public bool $int1_events_route_to_int2 = false,
        public bool $int2_events_route_to_int2 = false,
        public bool $boot_status_routed_to_int2 = false,
        public bool $activity_events_routed_to_int2 = false,
        public bool $interrupts_are_active_low = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->int2_tap_interrupt_enabled ? '1' : '0';
        $bit6 = $this->int1_events_route_to_int2 ? '1' : '0';
        $bit5 = $this->int2_events_route_to_int2 ? '1' : '0';
        $bit4 = $this->boot_status_routed_to_int2 ? '1' : '0';
        $bit3 = $this->activity_events_routed_to_int2 ? '1' : '0';
        $bit2 = '0';
        $bit1 = $this->interrupts_are_active_low ? '1' : '0';
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
        );
    }
}
