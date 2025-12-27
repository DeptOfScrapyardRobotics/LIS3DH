<?php

namespace ScrapyardIO\Sensors\Accelerometers\LIS3DH\Concerns;

use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Enums\LIS3DHCommand;
use ScrapyardIO\Sensors\Accelerometers\LIS3DH\Exceptions\LIS3DHException;
use ScrapyardIO\Support\DataManipulation\ByteRegister;

trait LIS3DHBootSequence
{
    // Control Register 1
    protected string $active_axis = 'xyz';
    protected int $data_rate = 400;
    protected bool $low_power_mode = false;

    // Control Register 4
    protected bool $block_data_update = true;
    protected int $full_scale_range = 2;
    protected int $resolution_bits = 12;
    protected int $spi_wires = 3;
    protected bool $self_test_enabled = false;
    protected bool $big_endian_enabled = false;

    /**
     * @return void
     * @throws LIS3DHException
     */
    public function readDeviceId(): void
    {
        [$device_id] = $this->readData(LIS3DHCommand::WHO_AM_I->value, 1);
        if($device_id != 0x33) throw LIS3DHException::invalidDeviceID($device_id, 0x33);
    }

    public function resetCtrlReg1(): void
    {
        $this->sendCommand([LIS3DHCommand::CONTROL_REGISTER_1->value, 0x07]);
    }

    public function setCtrlReg1(): void
    {
        $this->sendCommand([LIS3DHCommand::CONTROL_REGISTER_1->value, $this->ctrlReg1()]);
    }

    public function setCtrlReg4(): void
    {
        $this->sendCommand([LIS3DHCommand::CONTROL_REGISTER_4->value, $this->ctrlReg4()]);
        //$this->sendCommand([LIS3DHCommand::CONTROL_REGISTER_4->value, 0x88]);
    }

    public function setTempConfig(): void
    {
        $this->sendCommand([LIS3DHCommand::TEMP_CONFIG_REGISTER->value, 0x80]);
    }

    public function ctrlReg1(): int
    {
        $register = (new ByteRegister(0));

        switch($this->data_rate)
        {
            case 1:
                $register = $register->update(7, 0)
                    ->update(6, 0)
                    ->update(5, 0)
                    ->update(4, 1);
                break;

            case 10:
                $register = $register->update(7, 0)
                    ->update(6, 0)
                    ->update(5, 1)
                    ->update(4, 0);
                break;

            case 25:
                $register = $register->update(7, 0)
                    ->update(6, 0)
                    ->update(5, 1)
                    ->update(4, 1);
                break;

            case 50:
                $register = $register->update(7, 0)
                    ->update(6, 1)
                    ->update(5, 0)
                    ->update(4, 0);
                break;

            case 100:
                $register = $register->update(7, 0)
                    ->update(6, 1)
                    ->update(5, 0)
                    ->update(4, 1);
                break;

            case 200:
                $register = $register->update(7, 0)
                    ->update(6, 1)
                    ->update(5, 1)
                    ->update(4, 0);
                break;

            case 400:
                $register = $register->update(7, 0)
                    ->update(6, 1)
                    ->update(5, 1)
                    ->update(4, 1);
                break;

            case 1600:
                $register = $register->update(7, 1)
                    ->update(6, 0)
                    ->update(5, 0)
                    ->update(4, 0);
                break;

            case 1250:
            case 5000:
            $register = $register->update(7, 1)
                ->update(6, 0)
                ->update(5, 0)
                ->update(4, 1);
                break;

            default:
                $register = $register->update(7, 0)
                    ->update(6, 0)
                    ->update(5, 0)
                    ->update(4, 0);
        }

        $register = $register->update(3, $this->low_power_mode);

        switch($this->active_axis)
        {
            case "x":
                $register = $register->update(2, 0)
                    ->update(1, 0)
                    ->update(0, 1);
                break;

            case "y":
                $register = $register->update(2, 0)
                    ->update(1, 1)
                    ->update(0, 0);
                break;

            case "xy":
                $register = $register->update(2, 0)
                    ->update(1, 1)
                    ->update(0, 1);
                break;

            case "z":
                $register = $register->update(2, 1)
                    ->update(1, 0)
                    ->update(0, 0);
                break;

            case "xz":
                $register = $register->update(2, 1)
                    ->update(1, 0)
                    ->update(0, 1);
                break;

            case "yz":
                $register = $register->update(2, 1)
                    ->update(1, 1)
                    ->update(0, 0);
                break;

            case "xyz":
                $register = $register->update(2, 1)
                    ->update(1, 1)
                    ->update(0, 1);
                break;

            default:
                $register = $register->update(2, 0)
                    ->update(1, 0)
                    ->update(0, 0);
        }

        return $register->byte;
    }

    public function ctrlReg4(bool $from_device = false): int
    {
        $register = (new ByteRegister(0))
            ->update(7, $this->block_data_update);

        switch($this->full_scale_range)
        {
            case 4:
                $register = $register->update(6, 0)
                    ->update(5, 1);
                break;

            case 8:
                $register = $register->update(6, 1)
                    ->update(5, 0);
                break;

            case 16:
                $register = $register->update(6, 1)
                    ->update(5, 1);
                break;

            case 2:
            default:
            $register = $register->update(6, 0)
                ->update(5, 0);
        }
        return $register->update(4, $this->resolution_bits == 12)
            ->update(3, $this->spi_wires != 4)
            ->update(2, $this->self_test_enabled)
            ->update(1, $this->big_endian_enabled)
            ->update(0, 0)
            ->byte;
    }

    protected function convertTo16Bit(int $low, int $high): int
    {
        // Combine bytes into 16-bit value
        if(!$this->big_endian_enabled)
        {
            $result = unpack('s', pack('v', ($high << 8) | $low))[1];
        }
        else
        {
            $result = unpack('s', pack('n', ($high << 8) | $low))[1];
        }

        if($this->resolution_bits == 12)
        {
            $result  = $result >> 4;
        }
        elseif($this->resolution_bits == 10)
        {
            $result = $result >> 6;
        }

        return $result;
    }

    public function resoBits(): int
    {
        return $this->resolution_bits;
    }

    public function getFSR(): int
    {
        return $this->full_scale_range;
    }
}
