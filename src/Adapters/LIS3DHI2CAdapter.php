<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Adapters;

use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums\LIS3DHCommand;
use ScrapyardIO\Sensors\Enums\SensorType;
use ScrapyardIO\Support\Attributes\Sensor;
use ScrapyardIO\Sensors\Accelerometers\Adapters\AccelerometerAdapter;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns\LIS3DHI2CChip;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums\LIS3DHI2CAddress;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Exceptions\LIS3DHException;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns\LIS3DHBootSequence;


#[Sensor('LIS3DH', LIS3DHI2CAddress::SDO_GROUNDED->value, SensorType::ACCELEROMETER)]
class LIS3DHI2CAdapter extends AccelerometerAdapter
{
    use LIS3DHI2CChip;
    use LIS3DHBootSequence;

    public function bus(int $bus):static
    {
        $this->i2c_lis3dh_bus($bus);
        return $this;
    }

    public function address(LIS3DHI2CAddress $address):static
    {
        $this->i2c_lis3dh_address($address->value);
        return $this;
    }

    public function rawX(): int
    {
        [$low, $high] = $this->readData(LIS3DHCommand::X_AXIS_LOW->value | 0x80, 2);
        usleep(9000);
        return $this->convertTo16Bit($low, $high);
    }

    public function rawY(): int
    {
        [$low, $high] = $this->readData(LIS3DHCommand::Y_AXIS_LOW->value | 0x80, 2);
        usleep(9000);
        return $this->convertTo16Bit($low, $high);
    }

    public function rawZ(): int
    {
        [$low, $high] = $this->readData(LIS3DHCommand::Z_AXIS_LOW->value | 0x80, 2);
        usleep(9000);
        return $this->convertTo16Bit($low, $high);
    }

    public function rawXYZ(): array
    {
        [$x_low, $x_high, $y_low, $y_high, $z_low, $z_high] =
            $this->readData(LIS3DHCommand::X_AXIS_LOW->value | 0x80, 6);

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
        $this->lis3dh_i2c();

        $this->readDeviceId();

        $this->setCtrlReg1();
        $this->setCtrlReg4();

        $this->setTempConfig();

        return $this;
    }
}
