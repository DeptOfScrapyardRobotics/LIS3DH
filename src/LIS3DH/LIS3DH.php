<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\Enums\LIS3DxI2CAddress;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Concerns\LIS3DHAPI;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DxCarrierTransport;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DxException;
use Exception;
use GeneralPurposeIO\Circuits\Types\SensorIC;
use GeneralPurposeIO\Contracts\Circuits\Attributes\IntegratedCircuit;
use GeneralPurposeIO\Contracts\Circuits\Attributes\Pinout;
use GeneralPurposeIO\Contracts\Circuits\BootSequence;
use GeneralPurposeIO\I2C\I2C;
use GeneralPurposeIO\I2C\I2CSlave;
use GeneralPurposeIO\SPI\SPI;
use GeneralPurposeIO\SPI\SPIDevice;
use Waveforms\Contracts\Motion\MeasuresAcceleration;

#[IntegratedCircuit('I2C', 'SPI')]
#[Pinout(['I2C' => ['driver', 'device', 'slave']], ['SPI' => ['driver', 'device', 'chip_select']])]
class LIS3DH extends SensorIC implements BootSequence, MeasuresAcceleration
{
    use LIS3DHAPI;

    /**
     * @throws Exception
     */
    public function __construct(
        protected readonly LIS3DxCarrierTransport $transport,
        bool $boot_now = false,
    ) {
        if ($boot_now) {
            $this->boot();
        }
    }

    /**
     * @throws LIS3DxException
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'device_id' => $this->getDeviceId(),
            'range', 'fsr' => $this->getRange(),
            'data_rate' => $this->getDataRate(),
            'mode' => $this->getPerformanceMode(),
            'x' => $this->x(),
            'y' => $this->y(),
            'z' => $this->z(),
            default => throw LIS3DxException::invalidProperty($name, static::class),
        };
    }

    /**
     * @throws LIS3DxException
     */
    public function __set(string $name, mixed $value): void
    {
        match ($name) {
            'range', 'fsr' => $this->setRange($value),
            'data_rate' => $this->setDataRate($value),
            'mode' => $this->setPerformanceMode($value),
            default => throw LIS3DxException::invalidProperty($name, static::class),
        };
    }

    public function x(): float
    {
        return $this->calcLis3dhG($this->getRawX());
    }

    public function y(): float
    {
        return $this->calcLis3dhG($this->getRawY());
    }

    public function z(): float
    {
        return $this->calcLis3dhG($this->getRawZ());
    }

    public function close(): void
    {
        $this->transport->close();
    }

    /**
     * @throws LIS3DxException
     */
    public static function i2c(
        string|int $device,
        ?string $adapter = null,
        int $slave = LIS3DxI2CAddress::SA0_GROUNDED->value,
        bool $boot_now = true,
    ): static {
        $i2c = I2C::adapter($adapter)
            ->device($device)
            ->bus()
            ->slave($slave);

        return static::fromI2CBus($i2c, $boot_now);
    }

    /**
     * @throws LIS3DxException
     * @throws Exception
     */
    public static function fromI2CBus(I2CSlave $i2c, bool $boot_now = true): static
    {
        $transport = new LIS3DxCarrierTransport(i2c: $i2c);

        return new static($transport, $boot_now);
    }

    /**
     * @throws LIS3DxException
     */
    public static function spi(
        string|int $spi_device,
        string|int $chip_select,
        ?string $spi_adapter = null,
        bool $boot_now = true,
    ): static {
        $spi = SPI::adapter($spi_adapter)
            ->device($spi_device)
            ->mode(0)
            ->speed(500_000)
            ->bus()
            ->select($chip_select);

        return static::fromSPIBus($spi, $boot_now);
    }

    /**
     * @throws LIS3DxException
     * @throws Exception
     */
    public static function fromSPIBus(SPIDevice $spi, bool $boot_now = true): static
    {
        $transport = new LIS3DxCarrierTransport(spi: $spi);

        return new static($transport, $boot_now);
    }
}
