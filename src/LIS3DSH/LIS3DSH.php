<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH;

use BareMetal\IntegratedCircuit;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Adapters\LIS3DSHDataCarrier;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Concerns\LIS3DSHAPI;
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
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOutputDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHSelfTest;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Exceptions\LIS3DSHException;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Factory\LIS3DSHFactory;
use Exception;
use RealityInterface\Sensors\Attributes\MeasuresAcceleration;
use RealityInterface\Sensors\Contracts\Applied\Accelerometry\GenericAccelerometer;
use RealityInterface\Sensors\Enums\SensorType;
use Waveforms\Carriers\GPIO\GPIOBus;
use Waveforms\Carriers\I2C\I2C;
use Waveforms\Carriers\SPI\SPI;

/**
 * @property-read int $device_id
 * @property-read array $acceleration
 * @property-read int $raw_x
 * @property-read int $raw_y
 * @property-read int $raw_z
 * @property-read int $temperature
 * @property-read LIS3DSHStatusRegister $status
 * @property-read bool $data_ready
 * @property LIS3DSHOutputDataRate $output_data_rate
 * @property bool $block_data_update
 * @property bool $z_axis_enabled
 * @property bool $y_axis_enabled
 * @property bool $x_axis_enabled
 * @property LIS3DSHFullScale $full_scale
 * @property LIS3DSHAntiAliasingBandwidth $anti_aliasing_bandwidth
 * @property LIS3DSHSelfTest $self_test
 * @property bool $spi_3wire_mode
 * @property bool $data_ready_on_int1
 * @property bool $interrupts_active_high
 * @property bool $interrupts_pulsed
 * @property bool $int2_enabled
 * @property bool $int1_enabled
 * @property bool $vector_filter_enabled
 * @property-write bool $soft_reset
 * @property-write bool $reboot
 * @property bool $fifo_enabled
 * @property bool $watermark_enabled
 * @property bool $address_auto_increment
 * @property bool $int1_fifo_empty_enabled
 * @property bool $int1_fifo_watermark_enabled
 * @property bool $int1_fifo_overrun_enabled
 * @property bool $int2_boot_enabled
 * @property int $sm1_hysteresis
 * @property bool $sm1_routed_to_int2
 * @property bool $sm1_enabled
 * @property int $sm2_hysteresis
 * @property bool $sm2_routed_to_int2
 * @property bool $sm2_enabled
 * @property LIS3DSHFifoMode $fifo_mode
 * @property int $fifo_watermark
 * @property LIS3DSHControlRegister1 $control_register1
 * @property LIS3DSHControlRegister2 $control_register2
 * @property LIS3DSHControlRegister3 $control_register3
 * @property LIS3DSHControlRegister4 $control_register4
 * @property LIS3DSHControlRegister5 $control_register5
 * @property LIS3DSHControlRegister6 $control_register6
 * @property LIS3DSHFifoControlRegister $fifo_control_register
 */
#[MeasuresAcceleration(SensorType::ACCELEROMETER)]
class LIS3DSH extends IntegratedCircuit implements GenericAccelerometer
{
    use LIS3DSHAPI;

    protected bool $booted = false;

    protected int $hardwired_device_id = 0x3F;

    public function __construct(
        protected readonly LIS3DSHDataCarrier $carrier,
        protected readonly ?GPIOBus $gpio,
        LIS3DSHOutputDataRate $output_data_rate,
        LIS3DSHFullScale $full_scale,
    ) {
        $this->boot($output_data_rate, $full_scale);
    }

    /**
     * @throws LIS3DSHException
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'device_id' => $this->getDeviceId(),
            'acceleration' => $this->getAcceleration(),
            'raw_x' => $this->getRawX(),
            'raw_y' => $this->getRawY(),
            'raw_z' => $this->getRawZ(),
            'temperature' => $this->getTemperature(),
            'status' => $this->getStatus(),
            'data_ready' => $this->getDataReady(),
            'control_register1' => $this->getControlRegister1(),
            'control_register2' => $this->getControlRegister2(),
            'control_register3' => $this->getControlRegister3(),
            'control_register4' => $this->getControlRegister4(),
            'control_register5' => $this->getControlRegister5(),
            'control_register6' => $this->getControlRegister6(),
            'fifo_control_register' => $this->getFifoControlRegister(),
            'output_data_rate' => $this->getOutputDataRate(),
            'block_data_update' => $this->getBlockDataUpdate(),
            'z_axis_enabled' => $this->getZAxisEnabled(),
            'y_axis_enabled' => $this->getYAxisEnabled(),
            'x_axis_enabled' => $this->getXAxisEnabled(),
            'full_scale' => $this->getFullScale(),
            'anti_aliasing_bandwidth' => $this->getAntiAliasingBandwidth(),
            'self_test' => $this->getSelfTest(),
            'spi_3wire_mode' => $this->getSpi3WireMode(),
            'data_ready_on_int1' => $this->getDataReadyOnInt1(),
            'interrupts_active_high' => $this->getInterruptsActiveHigh(),
            'interrupts_pulsed' => $this->getInterruptsPulsed(),
            'int2_enabled' => $this->getInt2Enabled(),
            'int1_enabled' => $this->getInt1Enabled(),
            'vector_filter_enabled' => $this->getVectorFilterEnabled(),
            'fifo_enabled' => $this->getFifoEnabled(),
            'watermark_enabled' => $this->getWatermarkEnabled(),
            'address_auto_increment' => $this->getAddressAutoIncrement(),
            'int1_fifo_empty_enabled' => $this->getInt1FifoEmptyEnabled(),
            'int1_fifo_watermark_enabled' => $this->getInt1FifoWatermarkEnabled(),
            'int1_fifo_overrun_enabled' => $this->getInt1FifoOverrunEnabled(),
            'int2_boot_enabled' => $this->getInt2BootEnabled(),
            'sm1_hysteresis' => $this->getSm1Hysteresis(),
            'sm1_routed_to_int2' => $this->getSm1RoutedToInt2(),
            'sm1_enabled' => $this->getSm1Enabled(),
            'sm2_hysteresis' => $this->getSm2Hysteresis(),
            'sm2_routed_to_int2' => $this->getSm2RoutedToInt2(),
            'sm2_enabled' => $this->getSm2Enabled(),
            'fifo_mode' => $this->getFifoMode(),
            'fifo_watermark' => $this->getFifoWatermark(),
            default => throw LIS3DSHException::invalidProperty($name)
        };
    }

    /**
     * @throws LIS3DSHException
     */
    public function __set(string $name, mixed $value): void
    {
        match ($name) {
            'control_register1' => $this->setControlRegister1($value),
            'control_register2' => $this->setControlRegister2($value),
            'control_register3' => $this->setControlRegister3($value),
            'control_register4' => $this->setControlRegister4($value),
            'control_register5' => $this->setControlRegister5($value),
            'control_register6' => $this->setControlRegister6($value),
            'fifo_control_register' => $this->setFifoControlRegister($value),
            'output_data_rate' => $this->setOutputDataRate($value),
            'block_data_update' => $this->setBlockDataUpdate($value),
            'z_axis_enabled' => $this->setZAxisEnabled($value),
            'y_axis_enabled' => $this->setYAxisEnabled($value),
            'x_axis_enabled' => $this->setXAxisEnabled($value),
            'full_scale' => $this->setFullScale($value),
            'anti_aliasing_bandwidth' => $this->setAntiAliasingBandwidth($value),
            'self_test' => $this->setSelfTest($value),
            'spi_3wire_mode' => $this->setSpi3WireMode($value),
            'data_ready_on_int1' => $this->setDataReadyOnInt1($value),
            'interrupts_active_high' => $this->setInterruptsActiveHigh($value),
            'interrupts_pulsed' => $this->setInterruptsPulsed($value),
            'int2_enabled' => $this->setInt2Enabled($value),
            'int1_enabled' => $this->setInt1Enabled($value),
            'vector_filter_enabled' => $this->setVectorFilterEnabled($value),
            'soft_reset' => $this->softReset(),
            'reboot' => $this->reboot(),
            'fifo_enabled' => $this->setFifoEnabled($value),
            'watermark_enabled' => $this->setWatermarkEnabled($value),
            'address_auto_increment' => $this->setAddressAutoIncrement($value),
            'int1_fifo_empty_enabled' => $this->setInt1FifoEmptyEnabled($value),
            'int1_fifo_watermark_enabled' => $this->setInt1FifoWatermarkEnabled($value),
            'int1_fifo_overrun_enabled' => $this->setInt1FifoOverrunEnabled($value),
            'int2_boot_enabled' => $this->setInt2BootEnabled($value),
            'sm1_hysteresis' => $this->setSm1Hysteresis($value),
            'sm1_routed_to_int2' => $this->setSm1RoutedToInt2($value),
            'sm1_enabled' => $this->setSm1Enabled($value),
            'sm2_hysteresis' => $this->setSm2Hysteresis($value),
            'sm2_routed_to_int2' => $this->setSm2RoutedToInt2($value),
            'sm2_enabled' => $this->setSm2Enabled($value),
            'fifo_mode' => $this->setFifoMode($value),
            'fifo_watermark' => $this->setFifoWatermark($value),
            default => throw LIS3DSHException::invalidProperty($name)
        };
    }

    /**
     * @throws LIS3DSHException
     */
    protected function boot(LIS3DSHOutputDataRate $rate, LIS3DSHFullScale $full_scale): void
    {
        if (! $this->booted) {
            if ($this->device_id != $this->hardwired_device_id) {
                throw LIS3DSHException::invalidChipId($this->device_id);
            }

            $this->reboot();
            usleep(5000);

            $this->initializeCtrlReg4($rate);
            $this->initializeCtrlReg5($full_scale);

            $this->booted = true;
        }
    }

    /**
     * @throws Exception
     */
    public static function connection(string $driver): LIS3DSHFactory
    {
        return new LIS3DSHFactory(
            I2C::connection($driver),
            SPI::connection($driver)
        );
    }
}
