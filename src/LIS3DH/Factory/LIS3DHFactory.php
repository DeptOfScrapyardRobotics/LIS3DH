<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Factory;

use BareMetal\CircuitFactory;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Adapters\LIS3DHI2CAdapter;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Adapters\LIS3DHSPIAdapter;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use Exception;
use Waveforms\Carriers\GPIO\Factory\GPIOConnectionBuilder;
use Waveforms\Carriers\I2C\Factory\I2CConnectionBuilder;
use Waveforms\Carriers\I2C\I2CDevice;
use Waveforms\Carriers\SPI\Enums\SPIMode;
use Waveforms\Carriers\SPI\Factory\SPIConnectionBuilder;

class LIS3DHFactory extends CircuitFactory
{
    public string $consumer = 'lis3dh';

    public LIS3DHRange $range = LIS3DHRange::G2;

    protected ?GPIOConnectionBuilder $gpio_connection = null;

    public null|I2CConnectionBuilder|SPIConnectionBuilder $connection = null;

    public LIS3DHDataRate $rate = LIS3DHDataRate::HZ400;

    public function __construct(
        public I2CConnectionBuilder $i2c_connection,
        public SPIConnectionBuilder $spi_connection,
    ) {}

    public function i2c(string|int $chip_device, int $slave_address): static
    {
        $this->connection = $this->i2c_connection->firstly($chip_device)
            ->slaveAddress($slave_address);

        return $this;
    }

    public function spi(string|int $master, int $chip_select): static
    {
        $this->connection = $this->spi_connection->firstly($master)
            ->chip($chip_select)
            ->speed(1000000)
            ->mode(SPIMode::MODE_3);

        return $this;
    }

    public function int1(int $pin): static
    {
        return $this;
    }

    public function int2(int $pin): static
    {
        return $this;
    }

    public function consumer(string $consumer): static
    {
        $this->consumer = $consumer;

        return $this;
    }

    public function dataRate(LIS3DHDataRate $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function fullScaleRange(LIS3DHRange $range): static
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function create(): LIS3DH
    {
        $carrier = $this->connection?->boot();
        if (is_null($carrier)) {
            throw new Exception('A connection was not registered.');
        }

        if ($carrier instanceof I2CDevice) {
            $carrier = new LIS3DHI2CAdapter($carrier);
        } else {
            $carrier = new LIS3DHSPIAdapter($carrier);
        }

        $gpio = $this->gpio_connection?->consumer($this->consumer)->boot();

        return new LIS3DH(
            $carrier, $gpio,
            $this->rate,
            $this->range
        );
    }
}
