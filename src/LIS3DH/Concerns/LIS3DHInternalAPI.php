<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Concerns;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister1;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister4;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHOpCode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHReadRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHSelfTestOption;

trait LIS3DHInternalAPI
{
    protected function initializeCtrlReg1(LIS3DHDataRate $rate): void
    {
        if ($this->data_rate != LIS3DHDataRate::POWER_DOWN) {
            $this->control_register1 = new LIS3DHControlRegister1(
                LIS3DHDataRate::POWER_DOWN,
                false,
                false,
                false,
                false,
            );
        }

        if ($this->data_rate == LIS3DHDataRate::POWER_DOWN) {
            $this->data_rate = $rate;
            $this->z_axis_enabled = true;
            $this->y_axis_enaled = true;
            $this->x_axis_enabled = true;
        }
    }

    protected function initializeCtrlReg4(LIS3DHRange $range): void
    {
        $new_register = new LIS3DHControlRegister4(
            true,
            false,
            $range,
            true,
            LIS3DHSelfTestOption::DISABLED,
            false,
        );
        $this->setControlRegister4($new_register);
    }

    protected function initializeCtrlReg5(): void
    {
        // The only thing done to CTRL_REG5 is rebooting.
        $this->reboot_memory_content = true;
        usleep(5000);
    }

    protected function initializeTempConfigReg(): void
    {
        // The only thing done to CTRL_REG5 is to enable the ADC.
        $this->adc_enabled = true;
    }

    protected function s16le(int $lsb, int $msb): int
    {
        $value = (($msb & 0xFF) << 8) | ($lsb & 0xFF);

        return ($value & 0x8000) ? $value - 0x10000 : $value;
    }

    protected function write(LIS3DHOpCode $register_hex, array $command_data = []): ?int
    {
        return $this->carrier->write($register_hex->value, $command_data);
    }

    protected function read(LIS3DHReadRegister $register_hex, int $length): array
    {
        return $this->carrier->read($register_hex->value, $length);
    }
}
