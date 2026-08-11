---
type: Module
title: Package (0.7)
description: dept-of-scrapyard-robotics/lis3dx Composer identity, namespace, and discovery.
resource: composer.json
tags: [orientation, package, 0.7, lis3dx]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-11T19:55:00Z" }
verified: { by: null, at: null }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package composer.json
  - id: provider
    resource: src/LIS3DxServiceProvider.php
    title: LIS3DxServiceProvider
  - id: gitattributes
    resource: .gitattributes
    title: Dist export-ignore
---

# Identity

| Field | Value |
|-------|-------|
| Composer | `dept-of-scrapyard-robotics/lis3dx` **0.7.0** |
| PHP | `^8.4\|^8.5\|^8.6` |
| Namespace | `DeptOfScrapyardRobotics\Sensors\LIS3Dx\` → `src/` |
| Provider | `DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DxServiceProvider` (package root) |
| Catalog slugs | `lis3dh`, `lis3dsh` |

# Requires

| Package | Constraint |
|---------|------------|
| `fabricate/nuts-and-bolts` | `^0.7.0` |
| `gpio/circuits` | `^0.7.0` |
| `gpio/contracts` | `^0.7.0` |
| `gpio/digital` | `^0.7.0` |
| `gpio/i2c` | `^0.7.0` |
| `gpio/spi` | `^0.7.0` |
| `waveforms/contracts` | `^0.7.0` |

Suggested (optional): `microscrap/i2c`, `microscrap/spi`, `microscrap/mpsse` at `^0.7.0`.[^composer]

# Surface

- **LIS3DH** — solid driver: `BootSequence` + `MeasuresAcceleration`; `x()`/`y()`/`z()` return **g** (Adafruit `x_g` scale). Factories `i2c()` / `spi()` (SPI mode 0 @ 500 kHz). WHO_AM_I `0x33`, default I2C `0x18`.
- **LIS3DSH** — stub with the same Circuits / capability interface; boot and axis reads throw `notImplemented` until the distinct register map is ported.

# Discovery

`extra.scrapyard-io.providers` lists `LIS3DxServiceProvider`. That provider registers both catalog ICs, wires `lis3dx:make-profile` into `circuit:make-profile`, and registers the `lis3dx-smoke` sketch.[^provider]

```php
Circuit::profile('lis3dh_board'); // recipe ic => lis3dh|lis3dsh
```

# Dist

`.okf/` and `AGENTS.md` are `export-ignore` — Composer dist tarballs omit them.[^gitattributes]

[^composer]: Package composer.json
[^provider]: LIS3DxServiceProvider
[^gitattributes]: Dist export-ignore
