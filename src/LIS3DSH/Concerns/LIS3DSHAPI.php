<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Concerns;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister1;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister2;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister3;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister4;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister5;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHControlRegister6;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHFifoControlRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\DataObjects\LIS3DSHStatusRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHAntiAliasingBandwidth;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFifoMode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFullScale;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOpCode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOutputDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHReadRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHSelfTest;

trait LIS3DSHAPI
{
    use LIS3DSHInternalAPI;

    protected float $std_gravity = 9.80665;

    public function getDeviceId(): int
    {
        return $this->read(LIS3DSHReadRegister::REG_WHO_AM_I, 1)[0] ?? -1;
    }

    public function getControlRegister4(): LIS3DSHControlRegister4
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_CTRL_REG4, 1)[0] ?? 0x00;

        return LIS3DSHControlRegister4::fromByte($byte);
    }

    public function setControlRegister4(LIS3DSHControlRegister4 $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_CTRL_REG4, [$reg->toByte()]);
    }

    public function getControlRegister1(): LIS3DSHControlRegister1
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_CTRL_REG1, 1)[0] ?? 0x00;

        return LIS3DSHControlRegister1::fromByte($byte);
    }

    public function setControlRegister1(LIS3DSHControlRegister1 $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_CTRL_REG1, [$reg->toByte()]);
    }

    public function getControlRegister2(): LIS3DSHControlRegister2
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_CTRL_REG2, 1)[0] ?? 0x00;

        return LIS3DSHControlRegister2::fromByte($byte);
    }

    public function setControlRegister2(LIS3DSHControlRegister2 $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_CTRL_REG2, [$reg->toByte()]);
    }

    public function getControlRegister3(): LIS3DSHControlRegister3
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_CTRL_REG3, 1)[0] ?? 0x00;

        return LIS3DSHControlRegister3::fromByte($byte);
    }

    public function setControlRegister3(LIS3DSHControlRegister3 $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_CTRL_REG3, [$reg->toByte()]);
    }

    public function getControlRegister5(): LIS3DSHControlRegister5
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_CTRL_REG5, 1)[0] ?? 0x00;

        return LIS3DSHControlRegister5::fromByte($byte);
    }

    public function setControlRegister5(LIS3DSHControlRegister5 $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_CTRL_REG5, [$reg->toByte()]);
    }

    public function getControlRegister6(): LIS3DSHControlRegister6
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_CTRL_REG6, 1)[0] ?? 0x00;

        return LIS3DSHControlRegister6::fromByte($byte);
    }

    public function setControlRegister6(LIS3DSHControlRegister6 $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_CTRL_REG6, [$reg->toByte()]);
    }

    public function getFifoControlRegister(): LIS3DSHFifoControlRegister
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_FIFO_CTRL, 1)[0] ?? 0x00;

        return LIS3DSHFifoControlRegister::fromByte($byte);
    }

    public function setFifoControlRegister(LIS3DSHFifoControlRegister $reg): void
    {
        $this->write(LIS3DSHOpCode::REG_FIFO_CTRL, [$reg->toByte()]);
    }

    public function getStatus(): LIS3DSHStatusRegister
    {
        $byte = $this->read(LIS3DSHReadRegister::REG_STATUS, 1)[0] ?? 0x00;

        return LIS3DSHStatusRegister::fromByte($byte);
    }

    public function getDataReady(): bool
    {
        return $this->getStatus()->xyz_data_available;
    }

    // --- CTRL_REG4 fields -------------------------------------------------

    public function getOutputDataRate(): LIS3DSHOutputDataRate
    {
        return $this->getControlRegister4()->output_data_rate;
    }

    public function setOutputDataRate(LIS3DSHOutputDataRate $rate): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DSHControlRegister4(
            $rate,
            $register->block_data_update,
            $register->z_axis_enabled,
            $register->y_axis_enabled,
            $register->x_axis_enabled,
        );
        $this->setControlRegister4($new_register);
    }

    public function getBlockDataUpdate(): bool
    {
        return $this->getControlRegister4()->block_data_update;
    }

    public function setBlockDataUpdate(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DSHControlRegister4(
            $register->output_data_rate,
            $value,
            $register->z_axis_enabled,
            $register->y_axis_enabled,
            $register->x_axis_enabled,
        );
        $this->setControlRegister4($new_register);
    }

    public function getZAxisEnabled(): bool
    {
        return $this->getControlRegister4()->z_axis_enabled;
    }

    public function setZAxisEnabled(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DSHControlRegister4(
            $register->output_data_rate,
            $register->block_data_update,
            $value,
            $register->y_axis_enabled,
            $register->x_axis_enabled,
        );
        $this->setControlRegister4($new_register);
    }

    public function getYAxisEnabled(): bool
    {
        return $this->getControlRegister4()->y_axis_enabled;
    }

    public function setYAxisEnabled(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DSHControlRegister4(
            $register->output_data_rate,
            $register->block_data_update,
            $register->z_axis_enabled,
            $value,
            $register->x_axis_enabled,
        );
        $this->setControlRegister4($new_register);
    }

    public function getXAxisEnabled(): bool
    {
        return $this->getControlRegister4()->x_axis_enabled;
    }

    public function setXAxisEnabled(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DSHControlRegister4(
            $register->output_data_rate,
            $register->block_data_update,
            $register->z_axis_enabled,
            $register->y_axis_enabled,
            $value,
        );
        $this->setControlRegister4($new_register);
    }

    // --- CTRL_REG5 fields -------------------------------------------------

    public function getFullScale(): LIS3DSHFullScale
    {
        return $this->getControlRegister5()->full_scale;
    }

    public function setFullScale(LIS3DSHFullScale $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DSHControlRegister5(
            $register->anti_aliasing_bandwidth,
            $value,
            $register->self_test,
            $register->spi_3wire_mode,
        );
        $this->setControlRegister5($new_register);
    }

    public function getAntiAliasingBandwidth(): LIS3DSHAntiAliasingBandwidth
    {
        return $this->getControlRegister5()->anti_aliasing_bandwidth;
    }

    public function setAntiAliasingBandwidth(LIS3DSHAntiAliasingBandwidth $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DSHControlRegister5(
            $value,
            $register->full_scale,
            $register->self_test,
            $register->spi_3wire_mode,
        );
        $this->setControlRegister5($new_register);
    }

    public function getSelfTest(): LIS3DSHSelfTest
    {
        return $this->getControlRegister5()->self_test;
    }

    public function setSelfTest(LIS3DSHSelfTest $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DSHControlRegister5(
            $register->anti_aliasing_bandwidth,
            $register->full_scale,
            $value,
            $register->spi_3wire_mode,
        );
        $this->setControlRegister5($new_register);
    }

    public function getSpi3WireMode(): bool
    {
        return $this->getControlRegister5()->spi_3wire_mode;
    }

    public function setSpi3WireMode(bool $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DSHControlRegister5(
            $register->anti_aliasing_bandwidth,
            $register->full_scale,
            $register->self_test,
            $value,
        );
        $this->setControlRegister5($new_register);
    }

    // --- CTRL_REG3 fields -------------------------------------------------

    public function getDataReadyOnInt1(): bool
    {
        return $this->getControlRegister3()->data_ready_on_int1;
    }

    public function setDataReadyOnInt1(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $value,
            $register->interrupts_active_high,
            $register->interrupts_pulsed,
            $register->int2_enabled,
            $register->int1_enabled,
            $register->vector_filter_enabled,
            false,
        );
        $this->setControlRegister3($new_register);
    }

    public function getInterruptsActiveHigh(): bool
    {
        return $this->getControlRegister3()->interrupts_active_high;
    }

    public function setInterruptsActiveHigh(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $register->data_ready_on_int1,
            $value,
            $register->interrupts_pulsed,
            $register->int2_enabled,
            $register->int1_enabled,
            $register->vector_filter_enabled,
            false,
        );
        $this->setControlRegister3($new_register);
    }

    public function getInterruptsPulsed(): bool
    {
        return $this->getControlRegister3()->interrupts_pulsed;
    }

    public function setInterruptsPulsed(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $register->data_ready_on_int1,
            $register->interrupts_active_high,
            $value,
            $register->int2_enabled,
            $register->int1_enabled,
            $register->vector_filter_enabled,
            false,
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt2Enabled(): bool
    {
        return $this->getControlRegister3()->int2_enabled;
    }

    public function setInt2Enabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $register->data_ready_on_int1,
            $register->interrupts_active_high,
            $register->interrupts_pulsed,
            $value,
            $register->int1_enabled,
            $register->vector_filter_enabled,
            false,
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt1Enabled(): bool
    {
        return $this->getControlRegister3()->int1_enabled;
    }

    public function setInt1Enabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $register->data_ready_on_int1,
            $register->interrupts_active_high,
            $register->interrupts_pulsed,
            $register->int2_enabled,
            $value,
            $register->vector_filter_enabled,
            false,
        );
        $this->setControlRegister3($new_register);
    }

    public function getVectorFilterEnabled(): bool
    {
        return $this->getControlRegister3()->vector_filter_enabled;
    }

    public function setVectorFilterEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $register->data_ready_on_int1,
            $register->interrupts_active_high,
            $register->interrupts_pulsed,
            $register->int2_enabled,
            $register->int1_enabled,
            $value,
            false,
        );
        $this->setControlRegister3($new_register);
    }

    public function softReset(): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DSHControlRegister3(
            $register->data_ready_on_int1,
            $register->interrupts_active_high,
            $register->interrupts_pulsed,
            $register->int2_enabled,
            $register->int1_enabled,
            $register->vector_filter_enabled,
            true,
        );
        $this->setControlRegister3($new_register);
    }

    // --- CTRL_REG6 fields -------------------------------------------------

    public function reboot(): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            true,
            $register->fifo_enabled,
            $register->watermark_enabled,
            $register->address_auto_increment,
            $register->int1_fifo_empty_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_enabled,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getFifoEnabled(): bool
    {
        return $this->getControlRegister6()->fifo_enabled;
    }

    public function setFifoEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $value,
            $register->watermark_enabled,
            $register->address_auto_increment,
            $register->int1_fifo_empty_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_enabled,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getWatermarkEnabled(): bool
    {
        return $this->getControlRegister6()->watermark_enabled;
    }

    public function setWatermarkEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $register->fifo_enabled,
            $value,
            $register->address_auto_increment,
            $register->int1_fifo_empty_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_enabled,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getAddressAutoIncrement(): bool
    {
        return $this->getControlRegister6()->address_auto_increment;
    }

    public function setAddressAutoIncrement(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $register->fifo_enabled,
            $register->watermark_enabled,
            $value,
            $register->int1_fifo_empty_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_enabled,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getInt1FifoEmptyEnabled(): bool
    {
        return $this->getControlRegister6()->int1_fifo_empty_enabled;
    }

    public function setInt1FifoEmptyEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $register->fifo_enabled,
            $register->watermark_enabled,
            $register->address_auto_increment,
            $value,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_enabled,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getInt1FifoWatermarkEnabled(): bool
    {
        return $this->getControlRegister6()->int1_fifo_watermark_enabled;
    }

    public function setInt1FifoWatermarkEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $register->fifo_enabled,
            $register->watermark_enabled,
            $register->address_auto_increment,
            $register->int1_fifo_empty_enabled,
            $value,
            $register->int1_fifo_overrun_enabled,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getInt1FifoOverrunEnabled(): bool
    {
        return $this->getControlRegister6()->int1_fifo_overrun_enabled;
    }

    public function setInt1FifoOverrunEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $register->fifo_enabled,
            $register->watermark_enabled,
            $register->address_auto_increment,
            $register->int1_fifo_empty_enabled,
            $register->int1_fifo_watermark_enabled,
            $value,
            $register->int2_boot_enabled,
        );
        $this->setControlRegister6($new_register);
    }

    public function getInt2BootEnabled(): bool
    {
        return $this->getControlRegister6()->int2_boot_enabled;
    }

    public function setInt2BootEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DSHControlRegister6(
            false,
            $register->fifo_enabled,
            $register->watermark_enabled,
            $register->address_auto_increment,
            $register->int1_fifo_empty_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_enabled,
            $value,
        );
        $this->setControlRegister6($new_register);
    }

    // --- CTRL_REG1 / CTRL_REG2 (state machines) ---------------------------

    public function getSm1Hysteresis(): int
    {
        return $this->getControlRegister1()->hysteresis;
    }

    public function setSm1Hysteresis(int $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DSHControlRegister1(
            $value,
            $register->routed_to_int2,
            $register->state_machine_enabled,
        );
        $this->setControlRegister1($new_register);
    }

    public function getSm1RoutedToInt2(): bool
    {
        return $this->getControlRegister1()->routed_to_int2;
    }

    public function setSm1RoutedToInt2(bool $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DSHControlRegister1(
            $register->hysteresis,
            $value,
            $register->state_machine_enabled,
        );
        $this->setControlRegister1($new_register);
    }

    public function getSm1Enabled(): bool
    {
        return $this->getControlRegister1()->state_machine_enabled;
    }

    public function setSm1Enabled(bool $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DSHControlRegister1(
            $register->hysteresis,
            $register->routed_to_int2,
            $value,
        );
        $this->setControlRegister1($new_register);
    }

    public function getSm2Hysteresis(): int
    {
        return $this->getControlRegister2()->hysteresis;
    }

    public function setSm2Hysteresis(int $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DSHControlRegister2(
            $value,
            $register->routed_to_int2,
            $register->state_machine_enabled,
        );
        $this->setControlRegister2($new_register);
    }

    public function getSm2RoutedToInt2(): bool
    {
        return $this->getControlRegister2()->routed_to_int2;
    }

    public function setSm2RoutedToInt2(bool $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DSHControlRegister2(
            $register->hysteresis,
            $value,
            $register->state_machine_enabled,
        );
        $this->setControlRegister2($new_register);
    }

    public function getSm2Enabled(): bool
    {
        return $this->getControlRegister2()->state_machine_enabled;
    }

    public function setSm2Enabled(bool $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DSHControlRegister2(
            $register->hysteresis,
            $register->routed_to_int2,
            $value,
        );
        $this->setControlRegister2($new_register);
    }

    // --- FIFO_CTRL fields -------------------------------------------------

    public function getFifoMode(): LIS3DSHFifoMode
    {
        return $this->getFifoControlRegister()->mode;
    }

    public function setFifoMode(LIS3DSHFifoMode $value): void
    {
        $register = $this->getFifoControlRegister();
        $new_register = new LIS3DSHFifoControlRegister(
            $value,
            $register->watermark,
        );
        $this->setFifoControlRegister($new_register);
    }

    public function getFifoWatermark(): int
    {
        return $this->getFifoControlRegister()->watermark;
    }

    public function setFifoWatermark(int $value): void
    {
        $register = $this->getFifoControlRegister();
        $new_register = new LIS3DSHFifoControlRegister(
            $register->mode,
            $value,
        );
        $this->setFifoControlRegister($new_register);
    }

    // --- Measurement ------------------------------------------------------

    public function getRawAcceleration(): array
    {
        $register_bytes = $this->read(LIS3DSHReadRegister::REG_OUT_X_L, 6);

        return [
            'x' => $this->s16le($register_bytes[0] ?? 0, $register_bytes[1] ?? 0),
            'y' => $this->s16le($register_bytes[2] ?? 0, $register_bytes[3] ?? 0),
            'z' => $this->s16le($register_bytes[4] ?? 0, $register_bytes[5] ?? 0),
        ];
    }

    public function getRawX(): int
    {
        return $this->getRawAcceleration()['x'];
    }

    public function getRawY(): int
    {
        return $this->getRawAcceleration()['y'];
    }

    public function getRawZ(): int
    {
        return $this->getRawAcceleration()['z'];
    }

    public function getAcceleration(): array
    {
        $raw = $this->getRawAcceleration();
        $scale = $this->getFullScale()->scaleG();

        return [
            $raw['x'] * $scale * $this->std_gravity,
            $raw['y'] * $scale * $this->std_gravity,
            $raw['z'] * $scale * $this->std_gravity,
        ];
    }

    public function getTemperature(): int
    {
        $raw = $this->read(LIS3DSHReadRegister::REG_OUT_T, 1)[0] ?? 0;

        // OUT_T is an 8-bit two's complement value referenced to a +25 °C bias.
        return $this->signed8($raw) + 25;
    }
}
