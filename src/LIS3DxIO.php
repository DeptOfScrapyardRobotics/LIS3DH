<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx;

/**
 * LIS3DH / LIS3DSH register IO over I2C or SPI.
 *
 * SPI: bit7 = read, bit6 = multi-byte auto-increment (same framing as ADXL34x).
 * I2C: bit7 of the sub-address enables auto-increment for multi-byte reads.
 */
trait LIS3DxIO
{
    /**
     * @throws LIS3DxException
     */
    protected function spiRead(int $register, int $length): array
    {
        if (! is_null($this->spi)) {
            $addr_byte = 0x80 | ($register & 0x3F);
            if ($length > 1) {
                $addr_byte |= 0x40;
            }

            $tx = array_merge([$addr_byte], array_fill(0, $length, 0x00));
            $rx = $this->spi->transfer($tx);

            return array_slice($rx, 1, $length);
        }

        throw LIS3DxException::transportMissingProtocol();
    }

    /**
     * @throws LIS3DxException
     */
    protected function spiWrite(int $register, array $data = []): int
    {
        if (! is_null($this->spi)) {
            $addr_byte = $register & 0x3F;
            if (count($data) > 1) {
                $addr_byte |= 0x40;
            }
            $payload = [$addr_byte, ...$data];

            return $this->spi->write($payload);
        }

        throw LIS3DxException::transportMissingProtocol();
    }

    /**
     * @throws LIS3DxException
     */
    protected function i2cRead(int $register, int $length): array
    {
        if (! is_null($this->i2c)) {
            $sub = $this->getLowByte($register);
            if ($length > 1) {
                $sub |= 0x80;
            }

            return $this->i2c->writeRead([$sub], $length);
        }

        throw LIS3DxException::transportMissingProtocol();
    }

    /**
     * @throws LIS3DxException
     */
    protected function i2cWrite(int $register, array $data = []): int
    {
        if (! is_null($this->i2c)) {
            $payload = [$this->getLowByte($register), ...$data];

            return $this->i2c->write($payload);
        }

        throw LIS3DxException::transportMissingProtocol();
    }
}
