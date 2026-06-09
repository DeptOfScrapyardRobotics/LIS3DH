<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Concerns;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister4;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister5;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFullScale;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOpCode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOutputDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHReadRegister;

trait LIS3DSHInternalAPI
{
    protected function initializeCtrlReg4(LIS3DSHOutputDataRate $rate): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DSHControlRegister4(
            $rate,
            $register->block_data_update,
            true,
            true,
            true,
        );
        $this->setControlRegister4($new_register);
    }

    protected function initializeCtrlReg5(LIS3DSHFullScale $full_scale): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DSHControlRegister5(
            $register->anti_aliasing_bandwidth,
            $full_scale,
            $register->self_test,
            $register->spi_3wire_mode,
        );
        $this->setControlRegister5($new_register);
    }

    protected function s16le(int $lsb, int $msb): int
    {
        $value = (($msb & 0xFF) << 8) | ($lsb & 0xFF);

        return ($value & 0x8000) ? $value - 0x10000 : $value;
    }

    protected function signed8(int $byte): int
    {
        $value = $byte & 0xFF;

        return ($value & 0x80) ? $value - 0x100 : $value;
    }

    protected function write(LIS3DSHOpCode $register_hex, array $command_data = []): ?int
    {
        return $this->carrier->write($register_hex->value, $command_data);
    }

    protected function read(LIS3DSHReadRegister $register_hex, int $length): array
    {
        return $this->carrier->read($register_hex->value, $length);
    }
}
