<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Adapters;

use Waveforms\Carriers\SPI\SPIDevice;

class LIS3DSHSPIAdapter extends LIS3DSHDataCarrier
{
    public function __construct(
        SPIDevice $carrier
    ) {
        parent::__construct($carrier);
    }

    /**
     * The LIS3DSH SPI address byte is RW @ bit7 and AD[6:0] @ bits [6:0] — there is
     * no dedicated multi-byte (MB) bit. Burst transfers rely on the ADD_INC bit in
     * CTRL_REG6 (enabled by default) to advance the address every block of 8 clocks.
     */
    public function read(int $register_hex, int $length): array
    {
        $addr_byte = 0x80 | ($register_hex & 0x7F);

        /** @var SPIDevice $carrier */
        $carrier = &$this->carrier;
        $tx = array_merge([$addr_byte], array_fill(0, $length, 0x00));
        $rx = $carrier->transfer($tx);

        return array_slice($rx, 1, $length);
    }

    public function write(int $register_hex, array $command_data = []): int
    {
        $addr_byte = $register_hex & 0x7F;
        $payload = [$addr_byte, ...$command_data];

        return $this->carrier->write($payload);
    }
}
