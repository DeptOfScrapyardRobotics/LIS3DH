Introduction
============

PHP Package for the LIS3Dx-family of motion sensors.

Compatible I2C Interfaces
===============
The LIS3Dx sensors communicate with your device over I2C, the InterIntegrated Circuit Protocol.

You can interface with sensors like LIS3DH with this package the following ways:
* A Linux Single-Board Computer's exposed GPIO pins using the dedicated I2C SDA/SCL pins
* An MPSSE-enabled USB-to-Serial device such as an FT232H generally using D0 for SCL and D1 for SDA connected to nearly any Linux or MacOS USB port.

Compatible SPI Interfaces
===============
The LIS3Dx sensors also support SPI for direct register communication.

You can interface with sensors like LIS3DH over SPI with this package the following ways:
* A Linux Single-Board Computer's exposed GPIO pins using the dedicated SPI MOSI/MISO/SCK and CS pins
* An MPSSE-enabled USB-to-Serial device such as an FT232H generally using D0 for SCK, D1 for MOSI, D2 for MISO and D3 for CS connected to nearly any Linux or MacOS USB port.

Dependencies
=============
This package makes use of modules within:
* [The ScrapyardIO Framework](https://github.com/ScrapyardIO/framework)

This package also requires one of the following extensions in order to interface with I2C/SPI
* [POSI Extension v^0.4.0 or newer](https://github.com/php-io-extensions/posi)
* [FTDI Extension v^0.4.0 or newer](https://github.com/php-io-extensions/ftdi)

In addition, an extension wrapper package is needed

For ext-posi
* [Microscrap POSIX Package v0.4.0 or newer](https://github.com/microscrap/posix)
* [Microscrap Native I2C Package v0.4.0 or newer](https://github.com/microscrap/i2c)
* [Microscrap Native SPI Package v0.4.0 or newer](https://github.com/microscrap/spi)

For ext-ftdi
* [Microscrap FTDI Package v0.4.0 or newer](https://github.com/microscrap/ftdi)
* [Microscrap MPSSE Package v0.4.0 or newer](https://github.com/microscrap/mpsse)

Installing from Composer
====================
Inside the root of your PHP Project, simply require the LIS3Dx package from composer
```shell
composer require dept-of-scrapyard-robotics/lis3dx
```
Framework Configuration
====================

If you would like to use the ScrapyardIO Framework to bootstrap your sensor without
wasting lines configuring your sensor right in the script you can add your desired
configuration to scrapyard-io.php, such as in this example:

### I2C
```php

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHI2CAddress;

return [
    'boards' => [
        // For Native Configurations 
        'lis3dh-native' => [
            'class_name' => LIS3DH::class,
            'connection' => ['driver' => 'native'],
            'startup' => [
                'i2c' => [
                    'chip_device' => 1,
                    'slave_address' => LIS3DHI2CAddress::SDO_ENERGIZED->value,
                ],
            ],
        ],
        // For USB Configurations
        'lis3dh-usb' => [
            'class_name' => LIS3DH::class,
            'connection' => ['driver' => 'usb'],
            'startup' => [
                'i2c' => [
                    'chip_device' => 'ft232h',
                    'slave_address' => LIS3DHI2CAddress::SDO_ENERGIZED->value,
                ],
            ],
        ],        
    ]
];

```

### SPI
```php

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;

return [
    'boards' => [
        // For Native Configurations 
        'lis3dh-native' => [
            'class_name' => LIS3DH::class,
            'connection' => ['driver' => 'native'],
            'startup' => [
                'spi' => [
                    'master' => 0,
                    'chip_select' => 0,
                ],
            ],
        ],
        // For USB Configurations
        'lis3dh-usb' => [
            'class_name' => LIS3DH::class,
            'connection' => ['driver' => 'usb'],
            'startup' => [
                'spi' => [
                    'master' => 'ft232h',
                    'chip_select' => 0,
                ],
            ],
        ],        
    ]
];
```

Basic Usage
============

### Native (POSIX) I2C driver. (Single Board Computers)
```php
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHI2CAddress;

$native_i2c_sensor = LIS3DH::connection('native')
    ->i2c(1, LIS3DHI2CAddress::SDO_ENERGIZED->value)
    ->create()
    
[$x, $y, $z] = $native_i2c_sensor->acceleration;

```

### Native (POSIX) SPI driver. (Single Board Computers)
```php

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;

$native_spi_sensor = LIS3DH::connection('native')
    ->spi(0, 0)
    ->create()
    
[$x, $y, $z] = $native_spi_sensor->acceleration;

```

### USB (MPSSE) driver using I2C. (Linux and MacOS)
```php
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHI2CAddress;

$usb_i2c_sensor = LIS3DH::connection('usb')
    ->i2c('ft232h', LIS3DHI2CAddress::SDO_ENERGIZED->value)
    ->create()
    
[$x, $y, $z] = $usb_i2c_sensor->acceleration;

```

### USB (MPSSE) driver using SPI. (Linux and MacOS)
```php
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;

$usb_spi_sensor = LIS3DH::connection('usb')
    ->spi('ft232h', 0)
    ->create()
    
[$x, $y, $z] = $usb_spi_sensor->acceleration;
```
## Alternative Usage

### Using Through the Sensor Library (as an Accelerometer)
```php
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHI2CAddress;
use RealityInterface\Sensors\Applied\Accelerometry\Accelerometer;

$lis3dh = LIS3DH::connection('native')
    ->i2c(1, LIS3DHI2CAddress::SDO_ENERGIZED->value)
    ->create();
    
$sensor = Accelerometer::as($lis3dh);

$data = $sensor->getAcceleration();

```

### Using Through the Sensor Framework (with an autoloaded config) (as an Accelerometer)
```php

use RealityInterface\Sensors\Applied\Accelerometry\Accelerometer;

$sensor = Accelerometer::using('lis3dh');

$data = $sensor->getAcceleration();

```

## Advanced Usage

You can tune measurement behavior at runtime using the LIS3DH's configuration properties:

* `data_rate` controls output data rate and power consumption.
* `range` controls the full-scale measurement range (±2 g to ±16 g).
* `high_res_output` enables 12-bit high-resolution mode.
* `low_power_mode` enables 8-bit low-power mode.
* `block_data_update` prevents output registers from being updated until both MSB and LSB have been read.
* `hp_filter_mode` and `hp_cutoff` control the on-chip high-pass filter.
* `filtered_data_selection` routes HP-filtered data to the output registers and FIFO.
* `adc_enabled` and `internal_temp_enabled` control the auxiliary ADC and built-in temperature sensor.

```php
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHDataRate;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHHighPassCutoff;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHHighPassFilterMode;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHI2CAddress;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Enums\LIS3DHRange;

$sensor = LIS3DH::connection('native')
    ->i2c(1, LIS3DHI2CAddress::SDO_ENERGIZED->value)
    ->create();

// Set output data rate to 200 Hz
$sensor->data_rate = LIS3DHDataRate::HZ200;

// Set full-scale range to ±8 g
$sensor->range = LIS3DHRange::G8;

// Enable high-resolution 12-bit output
$sensor->high_res_output = true;

// Prevent half-updated register reads
$sensor->block_data_update = true;

// Apply HP filter to output data with autoreset on interrupt event
$sensor->hp_filter_mode = LIS3DHHighPassFilterMode::AUTORESET_ON_INTERRUPT_EVENT;
$sensor->hp_cutoff = LIS3DHHighPassCutoff::CUTOFF_01;
$sensor->filtered_data_selection = true;

// Enable the auxiliary ADC
$sensor->adc_enabled = true;
```

### Interrupt Routing

The LIS3DH provides two interrupt pins. `CTRL_REG3` routes events to `INT1` and `CTRL_REG6`
routes events to `INT2`. Individual routing flags can be set independently:

```php
// Route data-ready signal and tap interrupt to INT1
$sensor->int1_data_ready_signal_enabled = true;
$sensor->int1_tap_interrupt_enabled = true;

// Route click interrupt and interrupt generator 1 to INT2
$sensor->int2_tap_interrupt_enabled = true;
$sensor->int1_events_route_to_int2 = true;

// Make both pins active-low
$sensor->interrupts_are_active_low = true;
```

### Tap Detection

```php
// Configure default single-tap detection and route it to INT1
$sensor->tapped = true;  // calls setTap() internally

// Poll for a tap event
if ($sensor->tapped) {
    echo "Tap detected!";
}
```

### Shake Detection

```php
if ($sensor->shake()) {
    echo "Shake detected!";
}
```

### ADC Readings

The LIS3DH exposes three auxiliary ADC channels (1–3) that measure voltages in the
approximately 900–1200 mV range.

```php
// Enable ADC first
$sensor->adc_enabled = true;

// Read channel 1 raw (signed 16-bit integer)
$raw = $sensor->readADCraw([1]);

// Read channel 2 in millivolts
$mv = $sensor->readADCmV([2]);
```

## Calibration

The LIS3DH does not expose per-axis hardware offset registers, so calibration is performed
in software by collecting samples at a known orientation and applying a correction to
subsequent reads.

### Zero-G Offset Calibration

Place the sensor flat and level, then capture a baseline at rest:

```php
$samples = 50;
$sum_x = $sum_y = $sum_z = 0.0;

for ($i = 0; $i < $samples; $i++) {
    [$x, $y, $z] = $sensor->acceleration;
    $sum_x += $x;
    $sum_y += $y;
    $sum_z += $z;
    usleep(10000);
}

$gravity = 9.806;
$offset_x = $sum_x / $samples;              // should be ~0 m/s²
$offset_y = $sum_y / $samples;              // should be ~0 m/s²
$offset_z = ($sum_z / $samples) - $gravity; // should be ~0 m/s² after removing 1g

// Apply offsets to subsequent reads
[$raw_x, $raw_y, $raw_z] = $sensor->acceleration;
$cal_x = $raw_x - $offset_x;
$cal_y = $raw_y - $offset_y;
$cal_z = $raw_z - $offset_z;
```

### Range Selection and Sensitivity

Choosing the right range trades sensitivity for headroom:

| Range  | HR mode (12-bit) | Normal mode (10-bit) | Low-power (8-bit) |
|--------|-----------------|----------------------|--------------------|
| ±2 g   | 1 mg/LSB        | 4 mg/LSB             | 16 mg/LSB          |
| ±4 g   | 2 mg/LSB        | 8 mg/LSB             | 32 mg/LSB          |
| ±8 g   | 4 mg/LSB        | 16 mg/LSB            | 64 mg/LSB          |
| ±16 g  | 12 mg/LSB       | 48 mg/LSB            | 192 mg/LSB         |

For most motion-sensing applications, `G2` with `high_res_output = true` gives the best
resolution. Use `G8` or `G16` when measuring strong impacts or vibration.

## Sensor API

The getters and setters in this API interface with the device directly (register reads/writes),
so you can use property access while still working against the sensor itself.

> The properties documented in this section are for the **LIS3DH**. The LIS3DSH exposes a
> different register set — see [LIS3DSH Sensor API](#lis3dsh-sensor-api) below.

Readable Properties (Getters)
-----------------------------

* `$sensor->device_id`
  Reads and returns the LIS3DH device ID. Should return `0x33`.

* `$sensor->acceleration`
  Reads all three axes and returns a `[x, y, z]` array in m/s².

* `$sensor->tapped`
  Reads the CLICK_SRC register. Returns `1` if a tap was detected, `0` otherwise.

* `$sensor->data_rate`
  Returns the current `LIS3DHDataRate` output data rate.
  Possible values: `POWER_DOWN`, `HZ1`, `HZ10`, `HZ25`, `HZ50`, `HZ100`, `HZ200`,
  `HZ400`, `LOW_POWER_HZ1620`, `HZ1344_OR_LOW_POWER_HZ5376`.

* `$sensor->low_power_mode`
  Returns `true` if 8-bit low-power mode is enabled (CTRL_REG1 LPen bit).

* `$sensor->x_axis_enabled`
  Returns `true` if the X axis is active.

* `$sensor->y_axis_enaled`
  Returns `true` if the Y axis is active.

* `$sensor->z_axis_enabled`
  Returns `true` if the Z axis is active.

* `$sensor->range`
  Returns the current `LIS3DHRange` full-scale measurement range.
  Possible values: `G2`, `G4`, `G8`, `G16`.

* `$sensor->high_res_output`
  Returns `true` if 12-bit high-resolution output mode is enabled.

* `$sensor->block_data_update`
  Returns `true` if block data update is enabled (output registers not updated until both
  MSB and LSB have been read).

* `$sensor->big_endian_data_shape`
  Returns `true` if output data is MSB-first (big-endian).

* `$sensor->little_endian_data_shape`
  Returns `true` if output data is LSB-first (little-endian). Inverse of `big_endian_data_shape`.

* `$sensor->spi_3wire_mode`
  Returns `true` if 3-wire SPI mode is enabled.

* `$sensor->self_test_mode`
  Returns the current `LIS3DHSelfTestOption`.
  Possible values: `DISABLED`, `NORMAL_SELF_TEST`, `NEGATIVE_SIGN_SELF_TEST`, `RESERVED`.

* `$sensor->hp_filter_mode`
  Returns the current `LIS3DHHighPassFilterMode` (CTRL_REG2 bits 7:6).
  Possible values: `NORMAL_MODE_RESET_BY_READING_REFERENCE`, `REFERENCE_SIGNAL_FOR_FILTERING`,
  `NORMAL_MODE`, `AUTORESET_ON_INTERRUPT_EVENT`.

* `$sensor->hp_cutoff`
  Returns the current `LIS3DHHighPassCutoff` cutoff frequency selector (CTRL_REG2 bits 5:4).
  Possible values: `CUTOFF_00`, `CUTOFF_01`, `CUTOFF_10`, `CUTOFF_11`.

* `$sensor->filtered_data_selection`
  Returns `true` if HP-filtered data is routed to the output registers and FIFO.

* `$sensor->hp_filter_for_click_ints_enabled`
  Returns `true` if the HP filter is applied to the click detection path.

* `$sensor->hp_filter_for_int2_enabled`
  Returns `true` if the HP filter is applied to interrupt generator 2.

* `$sensor->hp_filter_for_int1_enabled`
  Returns `true` if the HP filter is applied to interrupt generator 1.

* `$sensor->int1_tap_interrupt_enabled`
  Returns `true` if the click/tap interrupt is routed to INT1.

* `$sensor->int1_gen_events_enabled`
  Returns `true` if interrupt generator 1 events are routed to INT1.

* `$sensor->int2_gen_events_enabled`
  Returns `true` if interrupt generator 2 events are routed to INT1.

* `$sensor->int1_data_ready_signal_enabled`
  Returns `true` if the XYZ data-ready signal is routed to INT1.

* `$sensor->int2_data_ready_signal_enabled`
  Returns `true` if the 321DA data-ready signal is routed to INT1.

* `$sensor->int1_fifo_watermark_enabled`
  Returns `true` if the FIFO watermark interrupt is routed to INT1.

* `$sensor->int1_fifo_overrun_interrupt_enabled`
  Returns `true` if the FIFO overrun interrupt is routed to INT1.

* `$sensor->int2_tap_interrupt_enabled`
  Returns `true` if the click/tap interrupt is routed to INT2.

* `$sensor->int1_events_route_to_int2`
  Returns `true` if interrupt generator 1 events are routed to INT2.

* `$sensor->int2_events_route_to_int2`
  Returns `true` if interrupt generator 2 events are routed to INT2.

* `$sensor->boot_status_routed_to_int2`
  Returns `true` if the boot status signal is routed to INT2.

* `$sensor->activity_events_routed_to_int2`
  Returns `true` if activity interrupt events are routed to INT2.

* `$sensor->interrupts_are_active_low`
  Returns `true` if both INT1 and INT2 pins are active-low.

* `$sensor->reboot_memory_content`
  Returns `true` if a memory content reboot is in progress.

* `$sensor->fifo_buffer_enabled`
  Returns `true` if the FIFO buffer is enabled.

* `$sensor->latch_int1`
  Returns `true` if INT1 is latched (interrupt signal held until source register is read).

* `$sensor->int1_4d_direction_detection`
  Returns `true` if 4D direction detection is enabled on INT1.

* `$sensor->interrupts_active_high`
  Returns `true` if interrupts are set to active-high polarity.

* `$sensor->int2_4d_direction_detection`
  Returns `true` if 4D direction detection is enabled on INT2.

* `$sensor->adc_enabled`
  Returns `true` if the auxiliary ADC pinout is enabled.

* `$sensor->internal_temp_enabled`
  Returns `true` if the internal temperature sensor is enabled.

* `$sensor->control_register1`
  Reads and returns the full `LIS3DHControlRegister1` data object.

* `$sensor->control_register2`
  Reads and returns the full `LIS3DHControlRegister2` data object.

* `$sensor->control_register3`
  Reads and returns the full `LIS3DHControlRegister3` data object.

* `$sensor->control_register4`
  Reads and returns the full `LIS3DHControlRegister4` data object.

* `$sensor->control_register5`
  Reads and returns the full `LIS3DHControlRegister5` data object.

* `$sensor->control_register6`
  Reads and returns the full `LIS3DHControlRegister6` data object.

* `$sensor->temp_config_register`
  Reads and returns the full `LIS3DHTempConfigRegister` data object.

Writable Properties (Setters)
-----------------------------

* `$sensor->data_rate = LIS3DHDataRate::HZ200;`
  Sets the output data rate.

* `$sensor->low_power_mode = true;`
  Enables or disables 8-bit low-power mode. Mutually exclusive with `high_res_output`.

* `$sensor->x_axis_enabled = true;`
  Enables or disables the X axis.

* `$sensor->y_axis_enaled = true;`
  Enables or disables the Y axis.

* `$sensor->z_axis_enabled = true;`
  Enables or disables the Z axis.

* `$sensor->range = LIS3DHRange::G8;`
  Sets the full-scale measurement range.

* `$sensor->high_res_output = true;`
  Enables or disables 12-bit high-resolution output mode.

* `$sensor->block_data_update = true;`
  Enables or disables block data update (prevents half-updated register reads).

* `$sensor->big_endian_data_shape = true;`
  Sets output byte order to MSB-first.

* `$sensor->little_endian_data_shape = true;`
  Sets output byte order to LSB-first. Writing `true` sets `big_endian_data_shape` to `false`
  and vice versa — they are quantum-entangled.

* `$sensor->spi_3wire_mode = true;`
  Enables or disables 3-wire SPI mode.

* `$sensor->self_test_mode = LIS3DHSelfTestOption::NORMAL_SELF_TEST;`
  Sets the self-test mode.

* `$sensor->hp_filter_mode = LIS3DHHighPassFilterMode::AUTORESET_ON_INTERRUPT_EVENT;`
  Sets the high-pass filter operating mode.

* `$sensor->hp_cutoff = LIS3DHHighPassCutoff::CUTOFF_01;`
  Sets the high-pass filter cutoff frequency selector.

* `$sensor->filtered_data_selection = true;`
  Routes HP-filtered data to the output registers and FIFO.

* `$sensor->hp_filter_for_click_ints_enabled = true;`
  Enables or disables the HP filter on the click detection path.

* `$sensor->hp_filter_for_int2_enabled = true;`
  Enables or disables the HP filter on interrupt generator 2.

* `$sensor->hp_filter_for_int1_enabled = true;`
  Enables or disables the HP filter on interrupt generator 1.

* `$sensor->int1_tap_interrupt_enabled = true;`
  Routes or removes the click/tap interrupt from INT1.

* `$sensor->int1_gen_events_enabled = true;`
  Routes or removes interrupt generator 1 events from INT1.

* `$sensor->int2_gen_events_enabled = true;`
  Routes or removes interrupt generator 2 events from INT1.

* `$sensor->int1_data_ready_signal_enabled = true;`
  Routes or removes the XYZ data-ready signal from INT1.

* `$sensor->int2_data_ready_signal_enabled = true;`
  Routes or removes the 321DA data-ready signal from INT1.

* `$sensor->int1_fifo_watermark_enabled = true;`
  Routes or removes the FIFO watermark interrupt from INT1.

* `$sensor->int1_fifo_overrun_interrupt_enabled = true;`
  Routes or removes the FIFO overrun interrupt from INT1.

* `$sensor->int2_tap_interrupt_enabled = true;`
  Routes or removes the click/tap interrupt from INT2.

* `$sensor->int1_events_route_to_int2 = true;`
  Routes or removes interrupt generator 1 events from INT2.

* `$sensor->int2_events_route_to_int2 = true;`
  Routes or removes interrupt generator 2 events from INT2.

* `$sensor->boot_status_routed_to_int2 = true;`
  Routes or removes the boot status signal from INT2.

* `$sensor->activity_events_routed_to_int2 = true;`
  Routes or removes activity interrupt events from INT2.

* `$sensor->interrupts_are_active_low = true;`
  Sets both INT1 and INT2 pins to active-low polarity. Default is active-high.

* `$sensor->reboot_memory_content = true;`
  Triggers a reboot of the internal memory content. The sensor reloads its trimming parameters.

* `$sensor->fifo_buffer_enabled = true;`
  Enables or disables the internal 32-level FIFO buffer.

* `$sensor->latch_int1 = true;`
  Latches INT1 so the interrupt pin stays asserted until the INT source register is read.

* `$sensor->int1_4d_direction_detection = true;`
  Enables or disables 4D direction detection on INT1.

* `$sensor->interrupts_active_high = true;`
  Sets interrupts to active-high polarity (CTRL_REG5).

* `$sensor->int2_4d_direction_detection = true;`
  Enables or disables 4D direction detection on INT2.

* `$sensor->adc_enabled = true;`
  Enables or disables the auxiliary ADC pinout.

* `$sensor->internal_temp_enabled = true;`
  Enables or disables the internal temperature sensor.

* `$sensor->control_register1 = new LIS3DHControlRegister1(...);`
  Writes the full CTRL_REG1 from a data object.

* `$sensor->control_register2 = new LIS3DHControlRegister2(...);`
  Writes the full CTRL_REG2 from a data object.

* `$sensor->control_register3 = new LIS3DHControlRegister3(...);`
  Writes the full CTRL_REG3 from a data object.

* `$sensor->control_register4 = new LIS3DHControlRegister4(...);`
  Writes the full CTRL_REG4 from a data object.

* `$sensor->control_register5 = new LIS3DHControlRegister5(...);`
  Writes the full CTRL_REG5 from a data object.

* `$sensor->control_register6 = new LIS3DHControlRegister6(...);`
  Writes the full CTRL_REG6 from a data object.

* `$sensor->temp_config_register = new LIS3DHTempConfigRegister(...);`
  Writes the full TEMP_CFG_REG from a data object.

* `$sensor->tapped = true;`
  Convenience setter. Configures default single-tap detection and enables the click interrupt
  on INT1.

## LIS3DSH Sensor API

The LIS3DSH follows the same property-access pattern as the LIS3DH: every getter and setter
reads from or writes to the device directly. Multi-setting registers are broken out into
`DataObjects` (CTRL_REG1–6, FIFO_CTRL, STATUS) and fields that only accept specific values
are enum-backed.

```php
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\LIS3DSH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHI2CAddress;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHFullScale;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Enums\LIS3DSHOutputDataRate;

$sensor = LIS3DSH::connection('native')
    ->i2c(1, LIS3DSHI2CAddress::SDO_ENERGIZED->value)
    ->create();

[$x, $y, $z] = $sensor->acceleration;

$sensor->full_scale = LIS3DSHFullScale::G4;
$sensor->output_data_rate = LIS3DSHOutputDataRate::HZ400;
```

Readable Properties (Getters)
-----------------------------

* `$sensor->device_id`
  Reads and returns the LIS3DSH device ID. Should return `0x3F`.

* `$sensor->acceleration`
  Reads all three axes and returns a `[x, y, z]` array in m/s².

* `$sensor->raw_x`, `$sensor->raw_y`, `$sensor->raw_z`
  Returns the signed 16-bit raw output for a single axis (no scaling applied).

* `$sensor->temperature`
  Reads OUT_T and returns the die temperature in °C (8-bit, referenced to a +25 °C bias).

* `$sensor->data_ready`
  Returns `true` when a new X/Y/Z sample set is available (STATUS ZYXDA bit).

* `$sensor->status`
  Reads and returns the full `LIS3DSHStatusRegister` data object (per-axis data-available
  and overrun flags).

* `$sensor->output_data_rate`
  Returns the current `LIS3DSHOutputDataRate`.
  Possible values: `POWER_DOWN`, `HZ3_125`, `HZ6_25`, `HZ12_5`, `HZ25`, `HZ50`, `HZ100`,
  `HZ400`, `HZ800`, `HZ1600`.

* `$sensor->block_data_update`
  Returns `true` if block data update is enabled (output registers not updated until both
  MSB and LSB have been read).

* `$sensor->x_axis_enabled`, `$sensor->y_axis_enabled`, `$sensor->z_axis_enabled`
  Returns `true` if the respective axis is active.

* `$sensor->full_scale`
  Returns the current `LIS3DSHFullScale` measurement range.
  Possible values: `G2`, `G4`, `G6`, `G8`, `G16`.

* `$sensor->anti_aliasing_bandwidth`
  Returns the current `LIS3DSHAntiAliasingBandwidth` filter setting.
  Possible values: `HZ800`, `HZ200`, `HZ400`, `HZ50`.

* `$sensor->self_test`
  Returns the current `LIS3DSHSelfTest` mode.
  Possible values: `NORMAL`, `POSITIVE`, `NEGATIVE`, `NOT_ALLOWED`.

* `$sensor->spi_3wire_mode`
  Returns `true` if 3-wire SPI mode (SIM) is enabled.

* `$sensor->data_ready_on_int1`
  Returns `true` if the DRDY signal is routed to INT1 (CTRL_REG3 DR_EN).

* `$sensor->interrupts_active_high`
  Returns `true` if interrupt signals are active-high (CTRL_REG3 IEA).

* `$sensor->interrupts_pulsed`
  Returns `true` if interrupt signals are pulsed rather than latched (CTRL_REG3 IEL).

* `$sensor->int1_enabled`
  Returns `true` if the INT1/DRDY signal is enabled.

* `$sensor->int2_enabled`
  Returns `true` if the INT2 signal is enabled.

* `$sensor->vector_filter_enabled`
  Returns `true` if the vector filter is enabled (CTRL_REG3 VFILT).

* `$sensor->fifo_enabled`
  Returns `true` if the FIFO buffer is enabled.

* `$sensor->watermark_enabled`
  Returns `true` if FIFO watermark level use is enabled.

* `$sensor->address_auto_increment`
  Returns `true` if the sub-address auto-increments during multi-byte access (ADD_INC).
  This must stay enabled for burst reads of the output registers.

* `$sensor->int1_fifo_empty_enabled`
  Returns `true` if the FIFO empty indication is routed to INT1.

* `$sensor->int1_fifo_watermark_enabled`
  Returns `true` if the FIFO watermark interrupt is routed to INT1.

* `$sensor->int1_fifo_overrun_enabled`
  Returns `true` if the FIFO overrun interrupt is routed to INT1.

* `$sensor->int2_boot_enabled`
  Returns `true` if the BOOT interrupt is routed to INT2.

* `$sensor->sm1_enabled`, `$sensor->sm2_enabled`
  Returns `true` if state machine 1 / 2 is enabled.

* `$sensor->sm1_hysteresis`, `$sensor->sm2_hysteresis`
  Returns the unsigned 3-bit (0–7) hysteresis applied to the state machine threshold.

* `$sensor->sm1_routed_to_int2`, `$sensor->sm2_routed_to_int2`
  Returns `true` if the state machine interrupt is routed to INT2 (otherwise INT1).

* `$sensor->fifo_mode`
  Returns the current `LIS3DSHFifoMode`.
  Possible values: `BYPASS`, `FIFO`, `STREAM`, `STREAM_TO_FIFO`, `BYPASS_TO_STREAM`,
  `BYPASS_TO_FIFO`.

* `$sensor->fifo_watermark`
  Returns the unsigned 5-bit (0–31) FIFO watermark pointer.

* `$sensor->control_register1` … `$sensor->control_register6`
  Reads and returns the full `LIS3DSHControlRegister1`…`LIS3DSHControlRegister6` data object.

* `$sensor->fifo_control_register`
  Reads and returns the full `LIS3DSHFifoControlRegister` data object.

Writable Properties (Setters)
-----------------------------

* `$sensor->output_data_rate = LIS3DSHOutputDataRate::HZ400;`
  Sets the output data rate and power mode.

* `$sensor->block_data_update = true;`
  Enables or disables block data update (prevents half-updated register reads).

* `$sensor->x_axis_enabled = true;`
  `$sensor->y_axis_enabled = true;`
  `$sensor->z_axis_enabled = true;`
  Enables or disables the respective axis.

* `$sensor->full_scale = LIS3DSHFullScale::G4;`
  Sets the full-scale measurement range.

* `$sensor->anti_aliasing_bandwidth = LIS3DSHAntiAliasingBandwidth::HZ200;`
  Sets the anti-aliasing filter bandwidth.

* `$sensor->self_test = LIS3DSHSelfTest::POSITIVE;`
  Sets the self-test mode.

* `$sensor->spi_3wire_mode = true;`
  Enables or disables 3-wire SPI mode.

* `$sensor->data_ready_on_int1 = true;`
  Routes or removes the DRDY signal from INT1.

* `$sensor->interrupts_active_high = true;`
  Sets interrupt signal polarity (active-high when `true`, active-low when `false`).

* `$sensor->interrupts_pulsed = true;`
  Sets interrupt signals to pulsed (`true`) or latched (`false`).

* `$sensor->int1_enabled = true;`
  `$sensor->int2_enabled = true;`
  Enables or disables the INT1/INT2 output signal.

* `$sensor->vector_filter_enabled = true;`
  Enables or disables the vector filter.

* `$sensor->soft_reset = true;`
  Triggers a soft reset (CTRL_REG3 STRT power-on-reset function).

* `$sensor->reboot = true;`
  Forces a reboot of the trimming parameters (CTRL_REG6 BOOT). Self-clears when complete.

* `$sensor->fifo_enabled = true;`
  Enables or disables the FIFO buffer.

* `$sensor->watermark_enabled = true;`
  Enables or disables FIFO watermark level use.

* `$sensor->address_auto_increment = true;`
  Enables or disables sub-address auto-increment on multi-byte access. Leave enabled for
  burst output reads.

* `$sensor->int1_fifo_empty_enabled = true;`
  Routes or removes the FIFO empty indication from INT1.

* `$sensor->int1_fifo_watermark_enabled = true;`
  Routes or removes the FIFO watermark interrupt from INT1.

* `$sensor->int1_fifo_overrun_enabled = true;`
  Routes or removes the FIFO overrun interrupt from INT1.

* `$sensor->int2_boot_enabled = true;`
  Routes or removes the BOOT interrupt from INT2.

* `$sensor->sm1_enabled = true;`
  `$sensor->sm2_enabled = true;`
  Enables or disables state machine 1 / 2.

* `$sensor->sm1_hysteresis = 3;`
  `$sensor->sm2_hysteresis = 3;`
  Sets the unsigned 3-bit (0–7) state machine threshold hysteresis.

* `$sensor->sm1_routed_to_int2 = true;`
  `$sensor->sm2_routed_to_int2 = true;`
  Routes the state machine interrupt to INT2 (`true`) or INT1 (`false`).

* `$sensor->fifo_mode = LIS3DSHFifoMode::STREAM;`
  Sets the FIFO operating mode.

* `$sensor->fifo_watermark = 16;`
  Sets the unsigned 5-bit (0–31) FIFO watermark pointer.

* `$sensor->control_register1 = new LIS3DSHControlRegister1(...);`
  … through `$sensor->control_register6 = new LIS3DSHControlRegister6(...);`
  Writes the full corresponding control register from a data object.

* `$sensor->fifo_control_register = new LIS3DSHFifoControlRegister(...);`
  Writes the full FIFO_CTRL register from a data object.
