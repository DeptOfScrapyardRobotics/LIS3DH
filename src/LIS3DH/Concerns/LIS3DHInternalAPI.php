<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Concerns;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHMode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHOpCode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DxException;
use Fabricate\NutsAndBolts\Concerns\Splices16Bits;
use GeneralPurposeIO\Contracts\Circuits\BootScaffolding;

trait LIS3DHInternalAPI
{
    use BootScaffolding, Splices16Bits;

    protected int $hardwired_device_id = 0x33;

    protected function sendCommand(LIS3DHOpCode $register, array $command_data = []): int
    {
        return $this->transport->write($register->value, $command_data);
    }

    protected function readData(LIS3DHOpCode $register, int $length): array
    {
        return $this->transport->read($register->value, $length);
    }

    /**
     * @throws LIS3DxException
     */
    protected function _boot(): void
    {
        $this->confirmDeviceId();
        // Enable XYZ axes (normal / HR configured next).
        $this->sendCommand(LIS3DHOpCode::CTRL1, [0x07]);
        $this->setDataRate(LIS3DHDataRate::HZ400);
        // High-res + BDU (Adafruit begin writes 0x88).
        $this->sendCommand(LIS3DHOpCode::CTRL4, [0x88]);
    }

    /**
     * @throws LIS3DxException
     */
    protected function confirmDeviceId(): void
    {
        if ($this->device_id != $this->hardwired_device_id) {
            throw LIS3DxException::invalidChipId($this->device_id, $this->hardwired_device_id);
        }
    }

    /**
     * Scale left-justified 16-bit sample to g (Adafruit x_g / y_g / z_g).
     */
    protected function calcLis3dhG(int $raw): float
    {
        $range = $this->getRange();
        $mode = $this->getPerformanceMode();
        $lsb = $range->baseLsbMg();

        $lsb = match ($mode) {
            LIS3DHMode::HIGH_RESOLUTION => intdiv($lsb, 4),
            LIS3DHMode::LOW_POWER => $lsb * 4,
            default => $lsb,
        };

        return $lsb * ((float) $raw / $mode->lsb16Divisor());
    }
}
