<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Adapters;

use ScrapyardIO\Sensors\Enums\SensorType;
use ScrapyardIO\Support\Attributes\Sensor;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums\LIS3DHCommand;
use ScrapyardIO\Sensors\Accelerometers\Adapters\AccelerometerAdapter;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns\LIS3DHSPIChip;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums\LIS3DHI2CAddress;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Exceptions\LIS3DHException;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns\LIS3DHBootSequence;

#[Sensor('LIS3DH', LIS3DHI2CAddress::SDO_GROUNDED->value + 1, SensorType::ACCELEROMETER)]
class LIS3DHSPIAdapter extends AccelerometerAdapter
{
    use LIS3DHSPIChip;
    use LIS3DHBootSequence;

    public function bus(int $bus):static
    {
        $this->spi_lis3dh_bus($bus);
        return $this;
    }

    public function chipSelect(int $cs):static
    {
        $this->spi_lis3dh_chip_select($cs);
        return $this;
    }

    public function rawX(): int
    {
        [$echo, $low, $high] = $this->readData(LIS3DHCommand::X_AXIS_LOW->value | 0x80 | 0x40, 2, true);
        usleep(9000);
        return $this->convertTo16Bit($low, $high);
    }

    public function rawY(): int
    {
        [$echo, $low, $high] = $this->readData(LIS3DHCommand::Y_AXIS_LOW->value | 0x80 | 0x40, 2, true);
        usleep(9000);
        return $this->convertTo16Bit($low, $high);
    }

    public function rawZ(): int
    {
        [$echo, $low, $high] = $this->readData(LIS3DHCommand::Z_AXIS_LOW->value | 0x80 | 0x40, 2, true);
        usleep(9000);
        return $this->convertTo16Bit($low, $high);
    }

    public function rawXYZ(): array
    {
        [$echo, $x_low, $x_high, $y_low, $y_high, $z_low, $z_high] =
            $this->readData(LIS3DHCommand::X_AXIS_LOW->value | 0x80 | 0x40, 6, true);
        usleep(9000);
        return [
            'x' => $this->convertTo16Bit($x_low, $x_high),
            'y' => $this->convertTo16Bit($y_low, $y_high),
            'z' => $this->convertTo16Bit($z_low, $z_high),
        ];
    }

    /**
     * @return $this
     * @throws LIS3DHException
     */
    public function boot(): static
    {
        $this->lis3dh_spi();

        $this->readDeviceId();
        $this->resetCtrlReg1();
        $this->setCtrlReg1();
        $this->setCtrlReg4();

        $this->setTempConfig();

        return $this;
    }

    public function readDeviceId(): void
    {
        [$echo, $device_id] = $this->readData(LIS3DHCommand::WHO_AM_I->value, 1, true);
        if($device_id != 0x33) throw LIS3DHException::invalidDeviceID($device_id, 0x33);
    }
}

