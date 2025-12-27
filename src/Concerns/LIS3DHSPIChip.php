<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns;

use ScrapyardIO\Transports\SPITransport;

trait LIS3DHSPIChip
{
    protected ?SPITransport $lis3dh_spi = null;
    protected int $lis3dh_spi_bus = 1;
    protected int $spi_lis3dh_chip_select = 0;
    protected int $max_packet_size = 0;

    protected function spi_lis3dh_bus(?int $bus = null): int
    {
        if(!is_null($bus))
        {
            $this->lis3dh_spi_bus = $bus;
        }
        return $this->lis3dh_spi_bus;
    }

    protected function spi_lis3dh_chip_select(?int $cs = null): int
    {
        if($cs)
        {
            $this->spi_lis3dh_chip_select = $cs;
        }
        return $this->spi_lis3dh_chip_select;
    }

    protected function lis3dh_spi(): ?SPITransport
    {
        if(empty($this->lis3dh_spi))
        {
            $this->lis3dh_spi = new SPITransport(
                $this->spi_lis3dh_bus(),
                $this->spi_lis3dh_chip_select(),
                3,
                1000000,
                0
            );
        }

        return $this->lis3dh_spi;
    }

    public function readData(int $command, int $num_bytes_to_read, bool $set_read_bit = false): array
    {
        return $this->lis3dh_spi()->read($command, $num_bytes_to_read, $set_read_bit);
    }

    public function sendCommand(array $bytes): void
    {
        $this->lis3dh_spi()->notify($bytes);
    }
}
