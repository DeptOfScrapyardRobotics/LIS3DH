<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx;

use Fabricate\NutsAndBolts\Concerns\Splices16Bits;
use GeneralPurposeIO\I2C\I2CSlave;
use GeneralPurposeIO\SPI\SPIDevice;

class LIS3DxCarrierTransport
{
    use LIS3DxIO, Splices16Bits;

    public readonly string $active_transport;

    /**
     * @throws LIS3DxException
     */
    public function __construct(
        protected ?I2CSlave $i2c = null,
        protected ?SPIDevice $spi = null,
    ) {
        $this->active_transport = $this->detectTransport();
    }

    public function write(int $register, array $data): int
    {
        $method = "{$this->active_transport}Write";

        return $this->{$method}($register, $data);
    }

    public function read(int $register, int $length): array
    {
        $method = "{$this->active_transport}Read";

        return $this->{$method}($register, $length);
    }

    /**
     * @throws LIS3DxException
     */
    protected function detectTransport(): string
    {
        if (! is_null($this->i2c)) {
            return 'i2c';
        }

        if (! is_null($this->spi)) {
            return 'spi';
        }

        throw LIS3DxException::transportMissingProtocol();
    }

    public function close(): void
    {
        $this->i2c?->close();
        $this->spi?->close();
    }
}
