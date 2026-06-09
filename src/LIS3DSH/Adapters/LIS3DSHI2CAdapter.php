<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Adapters;

use Waveforms\Carriers\I2C\I2CDevice;

class LIS3DSHI2CAdapter extends LIS3DSHDataCarrier
{
    public function __construct(
        I2CDevice $carrier
    ) {
        parent::__construct($carrier);
    }

    /**
     * On the LIS3DSH the sub-address is only the 7-bit register address; the MSb
     * is not a multi-byte flag. Auto-increment for burst reads is governed by the
     * ADD_INC bit in CTRL_REG6 (enabled by default), so the address is sent as-is.
     */
    public function read(int $register_hex, int $length): array
    {
        $reg_addr = $register_hex & 0x7F;

        /** @var I2CDevice $carrier */
        $carrier = &$this->carrier;

        return $carrier->readWrite([$reg_addr], $length);
    }

    public function write(int $register_hex, array $command_data = []): int
    {
        $payload = [$register_hex & 0x7F, ...$command_data];

        return $this->carrier->write($payload);
    }
}
