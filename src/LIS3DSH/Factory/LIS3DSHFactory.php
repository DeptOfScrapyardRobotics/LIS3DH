<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Factory;

use BareMetal\CircuitFactory;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Adapters\LIS3DSHI2CAdapter;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Adapters\LIS3DSHSPIAdapter;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFullScale;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOutputDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\LIS3DSH;
use Exception;
use Waveforms\Carriers\GPIO\Factory\GPIOConnectionBuilder;
use Waveforms\Carriers\I2C\Factory\I2CConnectionBuilder;
use Waveforms\Carriers\I2C\I2CDevice;
use Waveforms\Carriers\SPI\Enums\SPIMode;
use Waveforms\Carriers\SPI\Factory\SPIConnectionBuilder;

class LIS3DSHFactory extends CircuitFactory
{
    public string $consumer = 'lis3dsh';

    protected ?GPIOConnectionBuilder $gpio_connection = null;

    protected LIS3DSHFullScale $full_scale = LIS3DSHFullScale::G2;

    protected LIS3DSHOutputDataRate $output_data_rate = LIS3DSHOutputDataRate::HZ100;

    public null|I2CConnectionBuilder|SPIConnectionBuilder $connection = null;

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

    public function setFullScale(LIS3DSHFullScale $full_scale): static
    {
        $this->full_scale = $full_scale;

        return $this;
    }

    public function setOutputDataRate(LIS3DSHOutputDataRate $output_data_rate): static
    {
        $this->output_data_rate = $output_data_rate;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function create(): LIS3DSH
    {
        $carrier = $this->connection?->boot();
        if (is_null($carrier)) {
            throw new Exception('A connection was not registered.');
        }

        if ($carrier instanceof I2CDevice) {
            $carrier = new LIS3DSHI2CAdapter($carrier);
        } else {
            $carrier = new LIS3DSHSPIAdapter($carrier);
        }

        $gpio = $this->gpio_connection?->consumer($this->consumer)->boot();

        return new LIS3DSH(
            $carrier, $gpio,
            $this->output_data_rate,
            $this->full_scale,
        );
    }
}
