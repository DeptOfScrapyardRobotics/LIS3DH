<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Concerns;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister1;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister2;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister3;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister4;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister5;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHControlRegister6;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\DataObjects\LIS3DHTempConfigRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHHighPassCutoff;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHHighPassFilterMode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHOpCode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHReadRegister;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHSelfTestOption;

trait LIS3DHAPI
{
    use LIS3DHInternalAPI;

    public function getDeviceId(): int
    {
        return $this->read(LIS3DHReadRegister::WHO_AM_I_REGISTER, 1)[0] ?? -1;
    }

    public function getControlRegister1(): LIS3DHControlRegister1
    {
        $byte = $this->read(LIS3DHReadRegister::CTRL_REGISTER1, 1)[0] ?? 0x00;

        return LIS3DHControlRegister1::fromByte($byte);
    }

    public function setControlRegister1(LIS3DHControlRegister1 $reg): void
    {
        $this->write(LIS3DHOpCode::CTRL_REGISTER1, [$reg->toByte()]);
    }

    public function getTempConfigRegister(): LIS3DHTempConfigRegister
    {
        $byte = $this->read(LIS3DHReadRegister::TEMP_CONFIG_REGISTER, 1)[0] ?? 0x00;

        return LIS3DHTempConfigRegister::fromByte($byte);
    }

    public function setTempConfigRegister(LIS3DHTempConfigRegister $value): void
    {
        $this->write(LIS3DHOpCode::TEMP_CONFIG_REGISTER, [$value->toByte()]);
    }

    public function getControlRegister2(): LIS3DHControlRegister2
    {
        $byte = $this->read(LIS3DHReadRegister::CTRL_REGISTER2, 1)[0] ?? 0x00;

        return LIS3DHControlRegister2::fromByte($byte);
    }

    public function setControlRegister2(LIS3DHControlRegister2 $reg): void
    {
        $this->write(LIS3DHOpCode::CTRL_REGISTER2, [$reg->toByte()]);
    }

    public function getControlRegister3(): LIS3DHControlRegister3
    {
        $byte = $this->read(LIS3DHReadRegister::CTRL_REGISTER3, 1)[0] ?? 0x00;

        return LIS3DHControlRegister3::fromByte($byte);
    }

    public function setControlRegister3(LIS3DHControlRegister3 $reg): void
    {
        $this->write(LIS3DHOpCode::CTRL_REGISTER3, [$reg->toByte()]);
    }

    public function getControlRegister4(): LIS3DHControlRegister4
    {
        $byte = $this->read(LIS3DHReadRegister::CTRL_REGISTER4, 1)[0] ?? 0x00;

        return LIS3DHControlRegister4::fromByte($byte);
    }

    public function setControlRegister4(LIS3DHControlRegister4 $reg): void
    {
        $this->write(LIS3DHOpCode::CTRL_REGISTER4, [$reg->toByte()]);
    }

    public function getControlRegister5(): LIS3DHControlRegister5
    {
        $byte = $this->read(LIS3DHReadRegister::CTRL_REGISTER5, 1)[0] ?? 0x00;

        return LIS3DHControlRegister5::fromByte($byte);
    }

    public function setControlRegister5(LIS3DHControlRegister5 $reg): void
    {
        $this->write(LIS3DHOpCode::CTRL_REGISTER5, [$reg->toByte()]);
    }

    public function getControlRegister6(): LIS3DHControlRegister6
    {
        $byte = $this->read(LIS3DHReadRegister::CTRL_REGISTER6, 1)[0] ?? 0x00;

        return LIS3DHControlRegister6::fromByte($byte);
    }

    public function setControlRegister6(LIS3DHControlRegister6 $reg): void
    {
        $this->write(LIS3DHOpCode::CTRL_REGISTER6, [$reg->toByte()]);
    }

    public function getDataRate(): LIS3DHDataRate
    {
        return $this->getControlRegister1()->data_rate;
    }

    public function setDataRate(LIS3DHDataRate $rate): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DHControlRegister1(
            $rate,
            $register->low_power_mode,
            $register->z_axis_enabled,
            $register->y_axis_enabled,
            $register->x_axis_enabled
        );
        $this->setControlRegister1($new_register);
    }

    public function getLowPowerMode(): bool
    {
        return $this->getControlRegister1()->low_power_mode;
    }

    public function setLowPowerMode(bool $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DHControlRegister1(
            $register->data_rate,
            $value,
            $register->z_axis_enabled,
            $register->y_axis_enabled,
            $register->x_axis_enabled
        );
        $this->setControlRegister1($new_register);
    }

    public function getZAxisEnabled(): bool
    {
        return $this->getControlRegister1()->z_axis_enabled;
    }

    public function setZAxisEnabled(bool $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DHControlRegister1(
            $register->data_rate,
            $register->low_power_mode,
            $value,
            $register->y_axis_enabled,
            $register->x_axis_enabled
        );
        $this->setControlRegister1($new_register);
    }

    public function getYAxisEnaled(): bool
    {
        return $this->getControlRegister1()->y_axis_enabled;
    }

    public function setYAxisEnaled(bool $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DHControlRegister1(
            $register->data_rate,
            $register->low_power_mode,
            $register->z_axis_enabled,
            $value,
            $register->x_axis_enabled
        );
        $this->setControlRegister1($new_register);
    }

    public function getXAxisEnabled(): bool
    {
        return $this->getControlRegister1()->x_axis_enabled;
    }

    public function setXAxisEnabled(bool $value): void
    {
        $register = $this->getControlRegister1();
        $new_register = new LIS3DHControlRegister1(
            $register->data_rate,
            $register->low_power_mode,
            $register->z_axis_enabled,
            $register->y_axis_enabled,
            $value
        );
        $this->setControlRegister1($new_register);
    }

    public function getHpFilterMode(): LIS3DHHighPassFilterMode
    {
        return $this->getControlRegister2()->hp_filter_mode;
    }

    public function setHpFilterMode(LIS3DHHighPassFilterMode $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DHControlRegister2(
            $value,
            $register->hp_cutoff,
            $register->filtered_data_selection,
            $register->hp_filter_for_click_ints_enabled,
            $register->hp_filter_for_int2_enabled,
            $register->hp_filter_for_int1_enabled
        );
        $this->setControlRegister2($new_register);
    }

    public function getHpCutoff(): LIS3DHHighPassCutoff
    {
        return $this->getControlRegister2()->hp_cutoff;
    }

    public function setHpCutoff(LIS3DHHighPassCutoff $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DHControlRegister2(
            $register->hp_filter_mode,
            $value,
            $register->filtered_data_selection,
            $register->hp_filter_for_click_ints_enabled,
            $register->hp_filter_for_int2_enabled,
            $register->hp_filter_for_int1_enabled
        );
        $this->setControlRegister2($new_register);
    }

    public function getFilteredDataSelection(): bool
    {
        return $this->getControlRegister2()->filtered_data_selection;
    }

    public function setFilteredDataSelection(bool $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DHControlRegister2(
            $register->hp_filter_mode,
            $register->hp_cutoff,
            $value,
            $register->hp_filter_for_click_ints_enabled,
            $register->hp_filter_for_int2_enabled,
            $register->hp_filter_for_int1_enabled
        );
        $this->setControlRegister2($new_register);
    }

    public function getHpFilterForClickIntsEnabled(): bool
    {
        return $this->getControlRegister2()->hp_filter_for_click_ints_enabled;
    }

    public function setHpFilterForClickIntsEnabled(bool $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DHControlRegister2(
            $register->hp_filter_mode,
            $register->hp_cutoff,
            $register->filtered_data_selection,
            $value,
            $register->hp_filter_for_int2_enabled,
            $register->hp_filter_for_int1_enabled
        );
        $this->setControlRegister2($new_register);
    }

    public function getHpFilterForInt2Enabled(): bool
    {
        return $this->getControlRegister2()->hp_filter_for_int2_enabled;
    }

    public function setHpFilterForInt2Enabled(bool $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DHControlRegister2(
            $register->hp_filter_mode,
            $register->hp_cutoff,
            $register->filtered_data_selection,
            $register->hp_filter_for_click_ints_enabled,
            $value,
            $register->hp_filter_for_int1_enabled
        );
        $this->setControlRegister2($new_register);
    }

    public function getHpFilterForInt1Enabled(): bool
    {
        return $this->getControlRegister2()->hp_filter_for_int1_enabled;
    }

    public function setHpFilterForInt1Enabled(bool $value): void
    {
        $register = $this->getControlRegister2();
        $new_register = new LIS3DHControlRegister2(
            $register->hp_filter_mode,
            $register->hp_cutoff,
            $register->filtered_data_selection,
            $register->hp_filter_for_click_ints_enabled,
            $register->hp_filter_for_int2_enabled,
            $value
        );
        $this->setControlRegister2($new_register);
    }

    public function getInt1TapInterruptEnabled(): bool
    {
        return $this->getControlRegister3()->int1_tap_interrupt_enabled;
    }

    public function setInt1TapInterruptEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $value,
            $register->int1_gen_events_enabled,
            $register->int2_gen_events_enabled,
            $register->int1_data_ready_signal_enabled,
            $register->int2_data_ready_signal_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_interrupt_enabled
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt1GenEventsEnabled(): bool
    {
        return $this->getControlRegister3()->int1_gen_events_enabled;
    }

    public function setInt1GenEventsEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $register->int1_tap_interrupt_enabled,
            $value,
            $register->int2_gen_events_enabled,
            $register->int1_data_ready_signal_enabled,
            $register->int2_data_ready_signal_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_interrupt_enabled
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt2GenEventsEnabled(): bool
    {
        return $this->getControlRegister3()->int2_gen_events_enabled;
    }

    public function setInt2GenEventsEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $register->int1_tap_interrupt_enabled,
            $register->int1_gen_events_enabled,
            $value,
            $register->int1_data_ready_signal_enabled,
            $register->int2_data_ready_signal_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_interrupt_enabled
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt1DataReadySignalEnabled(): bool
    {
        return $this->getControlRegister3()->int1_data_ready_signal_enabled;
    }

    public function setInt1DataReadySignalEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $register->int1_tap_interrupt_enabled,
            $register->int1_gen_events_enabled,
            $register->int2_gen_events_enabled,
            $value,
            $register->int2_data_ready_signal_enabled,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_interrupt_enabled
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt2DataReadySignalEnabled(): bool
    {
        return $this->getControlRegister3()->int2_data_ready_signal_enabled;
    }

    public function setInt2DataReadySignalEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $register->int1_tap_interrupt_enabled,
            $register->int1_gen_events_enabled,
            $register->int2_gen_events_enabled,
            $register->int1_data_ready_signal_enabled,
            $value,
            $register->int1_fifo_watermark_enabled,
            $register->int1_fifo_overrun_interrupt_enabled
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt1FifoWatermarkEnabled(): bool
    {
        return $this->getControlRegister3()->int1_fifo_watermark_enabled;
    }

    public function setInt1FifoWatermarkEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $register->int1_tap_interrupt_enabled,
            $register->int1_gen_events_enabled,
            $register->int2_gen_events_enabled,
            $register->int1_data_ready_signal_enabled,
            $register->int2_data_ready_signal_enabled,
            $value,
            $register->int1_fifo_overrun_interrupt_enabled
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt1FifoOverrunInterruptEnabled(): bool
    {
        return $this->getControlRegister3()->int1_fifo_overrun_interrupt_enabled;
    }

    public function setInt1FifoOverrunInterruptEnabled(bool $value): void
    {
        $register = $this->getControlRegister3();
        $new_register = new LIS3DHControlRegister3(
            $register->int1_tap_interrupt_enabled,
            $register->int1_gen_events_enabled,
            $register->int2_gen_events_enabled,
            $register->int1_data_ready_signal_enabled,
            $register->int2_data_ready_signal_enabled,
            $register->int1_fifo_watermark_enabled,
            $value
        );
        $this->setControlRegister3($new_register);
    }

    public function getInt2TapInterruptEnabled(): bool
    {
        return $this->getControlRegister6()->int2_tap_interrupt_enabled;
    }

    public function setInt2TapInterruptEnabled(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DHControlRegister6(
            $value,
            $register->int1_events_route_to_int2,
            $register->int2_events_route_to_int2,
            $register->boot_status_routed_to_int2,
            $register->activity_events_routed_to_int2,
            $register->interrupts_are_active_low
        );
        $this->setControlRegister6($new_register);
    }

    public function getInt1EventsRouteToInt2(): bool
    {
        return $this->getControlRegister6()->int1_events_route_to_int2;
    }

    public function setInt1EventsRouteToInt2(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DHControlRegister6(
            $register->int2_tap_interrupt_enabled,
            $value,
            $register->int2_events_route_to_int2,
            $register->boot_status_routed_to_int2,
            $register->activity_events_routed_to_int2,
            $register->interrupts_are_active_low
        );
        $this->setControlRegister6($new_register);
    }

    public function getInt2EventsRouteToInt2(): bool
    {
        return $this->getControlRegister6()->int2_events_route_to_int2;
    }

    public function setInt2EventsRouteToInt2(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DHControlRegister6(
            $register->int2_tap_interrupt_enabled,
            $register->int1_events_route_to_int2,
            $value,
            $register->boot_status_routed_to_int2,
            $register->activity_events_routed_to_int2,
            $register->interrupts_are_active_low
        );
        $this->setControlRegister6($new_register);
    }

    public function getBootStatusRoutedToInt2(): bool
    {
        return $this->getControlRegister6()->boot_status_routed_to_int2;
    }

    public function setBootStatusRoutedToInt2(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DHControlRegister6(
            $register->int2_tap_interrupt_enabled,
            $register->int1_events_route_to_int2,
            $register->int2_events_route_to_int2,
            $value,
            $register->activity_events_routed_to_int2,
            $register->interrupts_are_active_low
        );
        $this->setControlRegister6($new_register);
    }

    public function getActivityEventsRoutedToInt2(): bool
    {
        return $this->getControlRegister6()->activity_events_routed_to_int2;
    }

    public function setActivityEventsRoutedToInt2(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DHControlRegister6(
            $register->int2_tap_interrupt_enabled,
            $register->int1_events_route_to_int2,
            $register->int2_events_route_to_int2,
            $register->boot_status_routed_to_int2,
            $value,
            $register->interrupts_are_active_low
        );
        $this->setControlRegister6($new_register);
    }

    public function getInterruptsAreActiveLow(): bool
    {
        return $this->getControlRegister6()->interrupts_are_active_low;
    }

    public function setInterruptsAreActiveLow(bool $value): void
    {
        $register = $this->getControlRegister6();
        $new_register = new LIS3DHControlRegister6(
            $register->int2_tap_interrupt_enabled,
            $register->int1_events_route_to_int2,
            $register->int2_events_route_to_int2,
            $register->boot_status_routed_to_int2,
            $register->activity_events_routed_to_int2,
            $value
        );
        $this->setControlRegister6($new_register);
    }

    public function getBlockDataUpdate(): bool
    {
        return $this->getControlRegister4()->block_data_update;
    }

    public function setBlockDataUpdate(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DHControlRegister4(
            $value,
            $register->big_endian_data_shape,
            $register->range,
            $register->high_res_output,
            $register->self_test_mode,
            $register->spi_3wire_mode
        );
        $this->setControlRegister4($new_register);
    }

    public function getBigEndianDataShape(): bool
    {
        return $this->getControlRegister4()->big_endian_data_shape;
    }

    public function setBigEndianDataShape(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DHControlRegister4(
            $register->block_data_update,
            $value,
            $register->range,
            $register->high_res_output,
            $register->self_test_mode,
            $register->spi_3wire_mode
        );
        $this->setControlRegister4($new_register);
    }

    public function getLittleEndianDataShape(): bool
    {
        return ! $this->getControlRegister4()->big_endian_data_shape;
    }

    public function setLittleEndianDataShape(bool $value): void
    {
        $this->setBigEndianDataShape(! $value);
    }

    public function getHighResOutput(): bool
    {
        return $this->getControlRegister4()->high_res_output;
    }

    public function setHighResOutput(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DHControlRegister4(
            $register->block_data_update,
            $register->big_endian_data_shape,
            $register->range,
            $value,
            $register->self_test_mode,
            $register->spi_3wire_mode
        );
        $this->setControlRegister4($new_register);
    }

    public function getSpi3WireMode(): bool
    {
        return $this->getControlRegister4()->spi_3wire_mode;
    }

    public function setSpi3WireMode(bool $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DHControlRegister4(
            $register->block_data_update,
            $register->big_endian_data_shape,
            $register->range,
            $register->high_res_output,
            $register->self_test_mode,
            $value
        );
        $this->setControlRegister4($new_register);
    }

    public function getFullScaleRange(): LIS3DHRange
    {
        return $this->getControlRegister4()->range;
    }

    public function setFullScaleRange(LIS3DHRange $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DHControlRegister4(
            $register->block_data_update,
            $register->big_endian_data_shape,
            $value,
            $register->high_res_output,
            $register->self_test_mode,
            $register->spi_3wire_mode
        );
        $this->setControlRegister4($new_register);
    }

    public function getSelfTestMode(): LIS3DHSelfTestOption
    {
        return $this->getControlRegister4()->self_test_mode;
    }

    public function setSelfTestMode(LIS3DHSelfTestOption $value): void
    {
        $register = $this->getControlRegister4();
        $new_register = new LIS3DHControlRegister4(
            $register->block_data_update,
            $register->big_endian_data_shape,
            $register->range,
            $register->high_res_output,
            $value,
            $register->spi_3wire_mode
        );
        $this->setControlRegister4($new_register);
    }

    public function getEnableADC(): bool
    {
        return $this->getTempConfigRegister()->adc_pinout_enabled;
    }

    public function setEnableADC(bool $value): void
    {
        $register = $this->getTempConfigRegister();
        $new_register = new LIS3DHTempConfigRegister(
            $value,
            $register->internal_temp_sensor_enabled
        );
        $this->setTempConfigRegister($new_register);
    }

    public function getEnableInternalTempSensor(): bool
    {
        return $this->getTempConfigRegister()->internal_temp_sensor_enabled;
    }

    public function setEnableInternalTempSensor(bool $value): void
    {
        $register = $this->getTempConfigRegister();
        $new_register = new LIS3DHTempConfigRegister(
            $register->adc_pinout_enabled,
            $value
        );
        $this->setTempConfigRegister($new_register);
    }

    public function getRebootMemoryContent(): bool
    {
        return $this->getControlRegister5()->reboot_memory_content;
    }

    public function setRebootMemoryContent(bool $flag): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DHControlRegister5(
            $flag,
            $register->fifo_buffer_enabled,
            $register->latch_int1,
            $register->dir_4D_detection_on_int1_enabled,
            $register->ints_active_high,
            $register->dir_4D_detection_on_int2_enabled
        );
        $this->setControlRegister5($new_register);
    }

    public function getFifoBufferEnabled(): bool
    {
        return $this->getControlRegister5()->fifo_buffer_enabled;
    }

    public function setFifoBufferEnabled(bool $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DHControlRegister5(
            $register->reboot_memory_content,
            $value,
            $register->latch_int1,
            $register->dir_4D_detection_on_int1_enabled,
            $register->ints_active_high,
            $register->dir_4D_detection_on_int2_enabled
        );
        $this->setControlRegister5($new_register);
    }

    public function getLatchInt1(): bool
    {
        return $this->getControlRegister5()->latch_int1;
    }

    public function setLatchInt1(bool $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DHControlRegister5(
            $register->reboot_memory_content,
            $register->fifo_buffer_enabled,
            $value,
            $register->dir_4D_detection_on_int1_enabled,
            $register->ints_active_high,
            $register->dir_4D_detection_on_int2_enabled
        );
        $this->setControlRegister5($new_register);
    }

    public function getInt14DDirectionDetection(): bool
    {
        return $this->getControlRegister5()->dir_4D_detection_on_int1_enabled;
    }

    public function setInt14DDirectionDetection(bool $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DHControlRegister5(
            $register->reboot_memory_content,
            $register->fifo_buffer_enabled,
            $register->latch_int1,
            $value,
            $register->ints_active_high,
            $register->dir_4D_detection_on_int2_enabled
        );
        $this->setControlRegister5($new_register);
    }

    public function getInterruptsActiveHigh(): bool
    {
        return $this->getControlRegister5()->ints_active_high;
    }

    public function setInterruptsActiveHigh(bool $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DHControlRegister5(
            $register->reboot_memory_content,
            $register->fifo_buffer_enabled,
            $register->latch_int1,
            $register->dir_4D_detection_on_int1_enabled,
            $value,
            $register->dir_4D_detection_on_int2_enabled
        );
        $this->setControlRegister5($new_register);
    }

    public function getInt24DDirectionDetection(): bool
    {
        return $this->getControlRegister5()->dir_4D_detection_on_int2_enabled;
    }

    public function setInt24DDirectionDetection(bool $value): void
    {
        $register = $this->getControlRegister5();
        $new_register = new LIS3DHControlRegister5(
            $register->reboot_memory_content,
            $register->fifo_buffer_enabled,
            $register->latch_int1,
            $register->dir_4D_detection_on_int1_enabled,
            $register->ints_active_high,
            $value
        );
        $this->setControlRegister5($new_register);
    }

    public function getAcceleration(): array
    {
        $register_bytes = $this->read(LIS3DHReadRegister::DATA_FROM_X_L_REGISTER, 6);
        $is_big_endian_data_shape = $this->getBigEndianDataShape();

        if ($is_big_endian_data_shape) {
            $raw_x = $this->s16le($register_bytes[1], $register_bytes[0]);
            $raw_y = $this->s16le($register_bytes[3], $register_bytes[2]);
            $raw_z = $this->s16le($register_bytes[5], $register_bytes[4]);
        } else {
            $raw_x = $this->s16le($register_bytes[0], $register_bytes[1]);
            $raw_y = $this->s16le($register_bytes[2], $register_bytes[3]);
            $raw_z = $this->s16le($register_bytes[4], $register_bytes[5]);
        }

        $divider = match ($this->range) {
            LIS3DHRange::G16 => 1365.0,
            LIS3DHRange::G8 => 4096.0,
            LIS3DHRange::G4 => 8190.0,
            LIS3DHRange::G2 => 16380.0,
        };

        $standard_gravity = 9.806;

        return [
            ($raw_x / $divider) * $standard_gravity,
            ($raw_y / $divider) * $standard_gravity,
            ($raw_z / $divider) * $standard_gravity,
        ];
    }

    public function readADCmV(array $adc): float
    {
        $raw = $this->readADCraw($adc);

        return 1800 + ($raw + 32512) * (-900 / 65024);
    }

    public function readADCraw(array $adc): int
    {
        $channel = (int) ($adc[0] ?? 0);
        if ($channel < 1 || $channel > 3) {
            throw new \InvalidArgumentException('ADC must be a value 1 to 3.');
        }

        $register = match ($channel) {
            1 => LIS3DHReadRegister::DATA_FROM_ADC1_L_REGISTER,
            2 => LIS3DHReadRegister::DATA_FROM_ADC2_L_REGISTER,
            3 => LIS3DHReadRegister::DATA_FROM_ADC3_L_REGISTER,
        };

        $bytes = $this->read($register, 2);

        return $this->s16le($bytes[0], $bytes[1]);
    }

    public function setTap(): void
    {
        $this->int1_tap_interrupt_enabled = true;
        $this->write(LIS3DHOpCode::CLICK_CONFIG_REGISTER, [0x15]);
        $this->write(LIS3DHOpCode::CLICK_THRESHOLD_REGISTER, [0x80 | 40]);
        $this->write(LIS3DHOpCode::TIME_LIMIT_REGISTER, [10]);
        $this->write(LIS3DHOpCode::TIME_LATENCY_REGISTER, [20]);
        $this->write(LIS3DHOpCode::TIME_WINDOW_REGISTER, [255]);
    }

    public function shake(): bool
    {
        $samples = 10;
        $shake_threshold = 20.0;
        $sum_of_squares = 0.0;

        for ($sample = 0; $sample < $samples; $sample++) {
            [$x, $y, $z] = $this->getAcceleration();
            $sum_of_squares += ($x * $x) + ($y * $y) + ($z * $z);
            usleep(10000);
        }

        $avg_total_acceleration = sqrt($sum_of_squares / $samples);

        return $avg_total_acceleration > $shake_threshold;
    }

    public function getTapped(): int
    {
        $raw_click_source = $this->read(LIS3DHReadRegister::CLICK_SOURCE_REGISTER, 1)[0] ?? 0;

        return (($raw_click_source & 0x40) > 0) ? 1 : 0;
    }
}
