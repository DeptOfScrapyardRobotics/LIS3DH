<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH;

use BareMetal\IntegratedCircuit;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Adapters\LIS3DHDataCarrier;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Concerns\LIS3DHAPI;
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
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHSelfTestOption;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Exceptions\LIS3DHException;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Factory\LIS3DHFactory;
use Exception;
use RealityInterface\Sensors\Attributes\MeasuresAcceleration;
use RealityInterface\Sensors\Contracts\Applied\Accelerometry\GenericAccelerometer;
use RealityInterface\Sensors\Enums\SensorType;
use Waveforms\Carriers\GPIO\GPIOBus;
use Waveforms\Carriers\I2C\I2C;
use Waveforms\Carriers\SPI\SPI;

/**
 * @property-read int $device_id
 * @property LIS3DHDataRate $data_rate
 * @property bool $low_power_mode
 * @property bool $z_axis_enabled
 * @property bool $y_axis_enaled
 * @property bool $x_axis_enabled
 * @property bool $reboot_memory_content
 * @property LIS3DHHighPassFilterMode $hp_filter_mode
 * @property LIS3DHHighPassCutoff $hp_cutoff
 * @property bool $filtered_data_selection
 * @property bool $hp_filter_for_click_ints_enabled
 * @property bool $hp_filter_for_int2_enabled
 * @property bool $hp_filter_for_int1_enabled
 * @property bool $int1_tap_interrupt_enabled
 * @property bool $int1_gen_events_enabled
 * @property bool $int2_gen_events_enabled
 * @property bool $int1_data_ready_signal_enabled
 * @property bool $int2_data_ready_signal_enabled
 * @property bool $int1_fifo_watermark_enabled
 * @property bool $int1_fifo_overrun_interrupt_enabled
 * @property bool $int2_tap_interrupt_enabled
 * @property bool $int1_events_route_to_int2
 * @property bool $int2_events_route_to_int2
 * @property bool $boot_status_routed_to_int2
 * @property bool $activity_events_routed_to_int2
 * @property bool $interrupts_are_active_low
 * @property bool $block_data_update
 * @property bool $big_endian_data_shape
 * @property bool $little_endian_data_shape
 * @property bool $high_res_output
 * @property bool $spi_3wire_mode
 * @property LIS3DHRange $range
 * @property LIS3DHSelfTestOption $self_test_mode
 * @property bool $adc_enabled
 * @property bool $internal_temp_enabled
 * @property bool $fifo_buffer_enabled
 * @property bool $latch_int1
 * @property bool $int1_4d_direction_detection
 * @property bool $interrupts_active_high
 * @property bool $int2_4d_direction_detection
 * @property-read int $tapped
 * @property LIS3DHControlRegister1 $control_register1
 * @property LIS3DHControlRegister2 $control_register2
 * @property LIS3DHControlRegister3 $control_register3
 * @property LIS3DHControlRegister4 $control_register4
 * @property LIS3DHControlRegister5 $control_register5
 * @property LIS3DHControlRegister6 $control_register6
 * @property LIS3DHTempConfigRegister $temp_config_register
 */
#[MeasuresAcceleration(SensorType::ACCELEROMETER)]
class LIS3DH extends IntegratedCircuit implements GenericAccelerometer
{
    use LIS3DHAPI;

    protected bool $booted = false;

    protected int $hardwired_device_id = 0x33;

    public function __construct(
        protected readonly LIS3DHDataCarrier $carrier,
        protected readonly ?GPIOBus $gpio,
        LIS3DHDataRate $rate,
        LIS3DHRange $range
    ) {
        $this->boot($rate, $range);
    }

    /**
     * @throws LIS3DHException
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'acceleration' => $this->getAcceleration(),
            'device_id' => $this->getDeviceId(),
            'control_register1' => $this->getControlRegister1(),
            'temp_config_register' => $this->getTempConfigRegister(),
            'control_register2' => $this->getControlRegister2(),
            'control_register3' => $this->getControlRegister3(),
            'control_register4' => $this->getControlRegister4(),
            'control_register5' => $this->getControlRegister5(),
            'control_register6' => $this->getControlRegister6(),
            'data_rate' => $this->getDataRate(),
            'low_power_mode' => $this->getLowPowerMode(),
            'z_axis_enabled' => $this->getZAxisEnabled(),
            'y_axis_enaled' => $this->getYAxisEnaled(),
            'x_axis_enabled' => $this->getXAxisEnabled(),
            'hp_filter_mode' => $this->getHpFilterMode(),
            'hp_cutoff' => $this->getHpCutoff(),
            'filtered_data_selection' => $this->getFilteredDataSelection(),
            'hp_filter_for_click_ints_enabled' => $this->getHpFilterForClickIntsEnabled(),
            'hp_filter_for_int2_enabled' => $this->getHpFilterForInt2Enabled(),
            'hp_filter_for_int1_enabled' => $this->getHpFilterForInt1Enabled(),
            'int1_tap_interrupt_enabled' => $this->getInt1TapInterruptEnabled(),
            'int1_gen_events_enabled' => $this->getInt1GenEventsEnabled(),
            'int2_gen_events_enabled' => $this->getInt2GenEventsEnabled(),
            'int1_data_ready_signal_enabled' => $this->getInt1DataReadySignalEnabled(),
            'int2_data_ready_signal_enabled' => $this->getInt2DataReadySignalEnabled(),
            'int1_fifo_watermark_enabled' => $this->getInt1FifoWatermarkEnabled(),
            'int1_fifo_overrun_interrupt_enabled' => $this->getInt1FifoOverrunInterruptEnabled(),
            'int2_tap_interrupt_enabled' => $this->getInt2TapInterruptEnabled(),
            'int1_events_route_to_int2' => $this->getInt1EventsRouteToInt2(),
            'int2_events_route_to_int2' => $this->getInt2EventsRouteToInt2(),
            'boot_status_routed_to_int2' => $this->getBootStatusRoutedToInt2(),
            'activity_events_routed_to_int2' => $this->getActivityEventsRoutedToInt2(),
            'interrupts_are_active_low' => $this->getInterruptsAreActiveLow(),
            'block_data_update' => $this->getBlockDataUpdate(),
            'big_endian_data_shape' => $this->getBigEndianDataShape(),
            'little_endian_data_shape' => $this->getLittleEndianDataShape(),
            'high_res_output' => $this->getHighResOutput(),
            'spi_3wire_mode' => $this->getSpi3WireMode(),
            'range' => $this->getFullScaleRange(),
            'self_test_mode' => $this->getSelfTestMode(),
            'adc_enabled' => $this->getEnableADC(),
            'internal_temp_enabled' => $this->getEnableInternalTempSensor(),
            'reboot_memory_content' => $this->getRebootMemoryContent(),
            'fifo_buffer_enabled' => $this->getFifoBufferEnabled(),
            'latch_int1' => $this->getLatchInt1(),
            'int1_4d_direction_detection' => $this->getInt14DDirectionDetection(),
            'interrupts_active_high' => $this->getInterruptsActiveHigh(),
            'int2_4d_direction_detection' => $this->getInt24DDirectionDetection(),
            'tapped' => $this->getTapped(),
            default => throw LIS3DHException::invalidProperty($name)
        };
    }

    /**
     * @throws LIS3DHException
     */
    public function __set(string $name, mixed $value): void
    {
        match ($name) {
            'control_register1' => $this->setControlRegister1($value),
            'temp_config_register' => $this->setTempConfigRegister($value),
            'control_register2' => $this->setControlRegister2($value),
            'control_register3' => $this->setControlRegister3($value),
            'control_register4' => $this->setControlRegister4($value),
            'control_register5' => $this->setControlRegister5($value),
            'control_register6' => $this->setControlRegister6($value),
            'data_rate' => $this->setDataRate($value),
            'low_power_mode' => $this->setLowPowerMode($value),
            'z_axis_enabled' => $this->setZAxisEnabled($value),
            'y_axis_enaled' => $this->setYAxisEnaled($value),
            'x_axis_enabled' => $this->setXAxisEnabled($value),
            'hp_filter_mode' => $this->setHpFilterMode($value),
            'hp_cutoff' => $this->setHpCutoff($value),
            'filtered_data_selection' => $this->setFilteredDataSelection($value),
            'hp_filter_for_click_ints_enabled' => $this->setHpFilterForClickIntsEnabled($value),
            'hp_filter_for_int2_enabled' => $this->setHpFilterForInt2Enabled($value),
            'hp_filter_for_int1_enabled' => $this->setHpFilterForInt1Enabled($value),
            'int1_tap_interrupt_enabled' => $this->setInt1TapInterruptEnabled($value),
            'int1_gen_events_enabled' => $this->setInt1GenEventsEnabled($value),
            'int2_gen_events_enabled' => $this->setInt2GenEventsEnabled($value),
            'int1_data_ready_signal_enabled' => $this->setInt1DataReadySignalEnabled($value),
            'int2_data_ready_signal_enabled' => $this->setInt2DataReadySignalEnabled($value),
            'int1_fifo_watermark_enabled' => $this->setInt1FifoWatermarkEnabled($value),
            'int1_fifo_overrun_interrupt_enabled' => $this->setInt1FifoOverrunInterruptEnabled($value),
            'int2_tap_interrupt_enabled' => $this->setInt2TapInterruptEnabled($value),
            'int1_events_route_to_int2' => $this->setInt1EventsRouteToInt2($value),
            'int2_events_route_to_int2' => $this->setInt2EventsRouteToInt2($value),
            'boot_status_routed_to_int2' => $this->setBootStatusRoutedToInt2($value),
            'activity_events_routed_to_int2' => $this->setActivityEventsRoutedToInt2($value),
            'interrupts_are_active_low' => $this->setInterruptsAreActiveLow($value),
            'block_data_update' => $this->setBlockDataUpdate($value),
            'big_endian_data_shape' => $this->setBigEndianDataShape($value),
            'little_endian_data_shape' => $this->setLittleEndianDataShape($value),
            'high_res_output' => $this->setHighResOutput($value),
            'spi_3wire_mode' => $this->setSpi3WireMode($value),
            'range' => $this->setFullScaleRange($value),
            'self_test_mode' => $this->setSelfTestMode($value),
            'adc_enabled' => $this->setEnableADC($value),
            'internal_temp_enabled' => $this->setEnableInternalTempSensor($value),
            'reboot_memory_content' => $this->setRebootMemoryContent($value),
            'fifo_buffer_enabled' => $this->setFifoBufferEnabled($value),
            'latch_int1' => $this->setLatchInt1($value),
            'int1_4d_direction_detection' => $this->setInt14DDirectionDetection($value),
            'interrupts_active_high' => $this->setInterruptsActiveHigh($value),
            'int2_4d_direction_detection' => $this->setInt24DDirectionDetection($value),
            'tapped' => $this->setTap(),

            default => throw LIS3DHException::invalidProperty($name)
        };
    }

    protected function boot(LIS3DHDataRate $rate, LIS3DHRange $range): void
    {
        if (! $this->booted) {
            if ($this->device_id != $this->hardwired_device_id) {
                throw LIS3DHException::invalidChipId($this->device_id);
            }

            $this->initializeCtrlReg5();
            $this->initializeCtrlReg1($rate);
            $this->initializeCtrlReg4($range);
            $this->initializeTempConfigReg();

            if (! is_null($this->gpio)) {
                $this->latch_int1 = true;
            }

            $this->booted = true;
        }
    }

    /**
     * @throws Exception
     */
    public static function connection(string $driver): LIS3DHFactory
    {
        return new LIS3DHFactory(
            I2C::connection($driver),
            SPI::connection($driver)
        );
    }
}
