<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns;

use ScrapyardIO\Transports\I2CTransport;

trait LIS3DHI2CChip
{
    protected ?I2CTransport $lis3dh_i2c = null;
    protected int $lis3dh_i2c_bus = 1;
    protected int $lis3dh_i2c_address = 0;
    protected int $max_packet_size = 0;

    protected function i2c_lis3dh_bus(?int $bus = null): int
    {
        if($bus)
        {
            $this->lis3dh_i2c_bus = $bus;
        }
        return $this->lis3dh_i2c_bus;
    }

    protected function i2c_lis3dh_address(?int $address = null): int
    {
        if($address)
        {
            $this->lis3dh_i2c_address = $address;
        }
        return $this->lis3dh_i2c_address;
    }

    protected function lis3dh_i2c(): ?I2CTransport
    {
        if(empty($this->lis3dh_i2c))
        {
            $this->lis3dh_i2c = new I2CTransport(
                $this->i2c_lis3dh_address(),
                $this->i2c_lis3dh_bus()
            );
        }

        return $this->lis3dh_i2c;
    }

    public function readData(int $command, int $num_bytes_to_read): array
    {
        $this->sendCommand([$command]);
        return $this->lis3dh_i2c()->read($num_bytes_to_read);
    }

    public function sendData(array $bytes): void
    {

    }

    public function sendCommand(array $bytes): void
    {
        $this->lis3dh_i2c()->notify($bytes);
    }
}
