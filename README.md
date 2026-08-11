# dept-of-scrapyard-robotics/lis3dx (0.7)

I2C/SPI drivers for LIS3DH (solid) / LIS3DSH (stub). Extends `GeneralPurposeIO\Circuits\Types\SensorIC` and implements `Waveforms\Contracts\Motion\MeasuresAcceleration` (`x()` / `y()` / `z()` in **g**).

## Register

Provider registers catalog slugs `lis3dh`, `lis3dsh` and wires `lis3dx:make-profile` into `circuit:make-profile`.

## Profiles

```bash
workshop vendor:publish --tag=gpio-circuits-config
workshop circuit:make-profile          # picks any installed IC; LIS3Dx delegates here
workshop lis3dx:make-profile           # LIS3DH / LIS3DSH only
```

SPI profile/factory params use `spi_device` / `spi_adapter` (not bare `device` / `adapter`).

```php
$imu = Circuit::profile('lis3dh_board');
$imu->x(); // g
$imu->y();
$imu->z();
```

## Smoke sketch

```bash
php workshop runner lis3dx-smoke
php workshop runner lis3dx-smoke --profile=lis3dh_board
```
