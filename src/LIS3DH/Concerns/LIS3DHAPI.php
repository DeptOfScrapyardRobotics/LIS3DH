<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Concerns;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHMode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHOpCode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;

trait LIS3DHAPI
{
    use LIS3DHInternalAPI;

    public function getDeviceId(): int
    {
        [$id] = $this->readData(LIS3DHOpCode::WHO_AM_I, 1);

        return $id;
    }

    public function getRange(): LIS3DHRange
    {
        $ctrl4 = $this->readData(LIS3DHOpCode::CTRL4, 1)[0] ?? 0;
        $fs = ($ctrl4 >> 4) & 0x03;

        return LIS3DHRange::from($fs);
    }

    public function setRange(LIS3DHRange $range): void
    {
        $ctrl4 = $this->readData(LIS3DHOpCode::CTRL4, 1)[0] ?? 0;
        $ctrl4 = ($ctrl4 & ~(0x03 << 4)) | (($range->value & 0x03) << 4);
        $this->sendCommand(LIS3DHOpCode::CTRL4, [$ctrl4]);
        usleep(15_000);
    }

    public function getDataRate(): LIS3DHDataRate
    {
        $ctrl1 = $this->readData(LIS3DHOpCode::CTRL1, 1)[0] ?? 0;
        $odr = ($ctrl1 >> 4) & 0x0F;

        return LIS3DHDataRate::from($odr);
    }

    public function setDataRate(LIS3DHDataRate $rate): void
    {
        $ctrl1 = $this->readData(LIS3DHOpCode::CTRL1, 1)[0] ?? 0;
        $ctrl1 = ($ctrl1 & 0x0F) | (($rate->value & 0x0F) << 4);
        $this->sendCommand(LIS3DHOpCode::CTRL1, [$ctrl1]);
    }

    public function getPerformanceMode(): LIS3DHMode
    {
        $ctrl1 = $this->readData(LIS3DHOpCode::CTRL1, 1)[0] ?? 0;
        $ctrl4 = $this->readData(LIS3DHOpCode::CTRL4, 1)[0] ?? 0;
        $lp = (($ctrl1 >> 3) & 0x01) === 1;
        $hr = (($ctrl4 >> 3) & 0x01) === 1;

        if ($lp && ! $hr) {
            return LIS3DHMode::LOW_POWER;
        }

        if (! $lp && $hr) {
            return LIS3DHMode::HIGH_RESOLUTION;
        }

        return LIS3DHMode::NORMAL;
    }

    public function setPerformanceMode(LIS3DHMode $mode): void
    {
        $ctrl1 = $this->readData(LIS3DHOpCode::CTRL1, 1)[0] ?? 0;
        $ctrl4 = $this->readData(LIS3DHOpCode::CTRL4, 1)[0] ?? 0;

        if ($mode === LIS3DHMode::LOW_POWER) {
            $ctrl4 = $ctrl4 & ~(1 << 3);
            $ctrl1 = $ctrl1 | (1 << 3);
        } elseif ($mode === LIS3DHMode::HIGH_RESOLUTION) {
            $ctrl1 = $ctrl1 & ~(1 << 3);
            $ctrl4 = $ctrl4 | (1 << 3);
        } else {
            $ctrl1 = $ctrl1 & ~(1 << 3);
            $ctrl4 = $ctrl4 & ~(1 << 3);
        }

        $this->sendCommand(LIS3DHOpCode::CTRL1, [$ctrl1]);
        $this->sendCommand(LIS3DHOpCode::CTRL4, [$ctrl4]);

        usleep($mode === LIS3DHMode::HIGH_RESOLUTION ? 7_000 : 1_000);
    }

    /**
     * @return array{0:int,1:int,2:int} raw left-justified 16-bit XYZ
     */
    public function readRawAxes(): array
    {
        $data = $this->readData(LIS3DHOpCode::OUT_X_L, 6);

        return [
            $this->s16le($data[0] ?? 0, $data[1] ?? 0),
            $this->s16le($data[2] ?? 0, $data[3] ?? 0),
            $this->s16le($data[4] ?? 0, $data[5] ?? 0),
        ];
    }

    public function getRawX(): int
    {
        return $this->readRawAxes()[0];
    }

    public function getRawY(): int
    {
        return $this->readRawAxes()[1];
    }

    public function getRawZ(): int
    {
        return $this->readRawAxes()[2];
    }

    public function haveNewData(): bool
    {
        $status = $this->readData(LIS3DHOpCode::STATUS, 1)[0] ?? 0;

        return (($status >> 3) & 0x01) === 1;
    }
}
