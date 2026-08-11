# Agent guidelines — dept-of-scrapyard-robotics/lis3dx

## Knowledge Bundle (OKF)

This package ships an Open Knowledge Format bundle at [`.okf/`](.okf/) (excluded from Composer dist via `.gitattributes` `export-ignore`).

Before changing this package or advising on LIS3Dx architecture:

1. Read [`.okf/index.md`](.okf/index.md) first (progressive disclosure).
2. Open only the linked concepts needed for the task.
3. Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
4. When you learn something durable about **this package**, update the affected `.okf` concept(s) and append `.okf/log.md`.
5. Keep the `.okf` bundle at the **package root** only — do not nest extra `.okf` folders under `src/`.
6. Circuits registry semantics belong in `scrapyard-io/gpio-framework`’s `.okf`. Waveforms capability contracts belong in `scrapyard-io/waveforms`.

## Package rules (quick) — 0.7.x

- Composer: `dept-of-scrapyard-robotics/lis3dx` **0.7.0**. Namespace `DeptOfScrapyardRobotics\Sensors\LIS3Dx\`.
- Provider: `LIS3DxServiceProvider` at package root. Catalog slugs `lis3dh`, `lis3dsh`. Command `lis3dx:make-profile`. Sketch `lis3dx-smoke`.
- ICs extend `GeneralPurposeIO\Circuits\Types\SensorIC`, implement `BootSequence` + `MeasuresAcceleration`; factories `i2c(...)` / `spi(...)`.
- Acceleration samples from `x()` / `y()` / `z()` are in **g** (Adafruit `x_g` / `y_g` / `z_g` scale), not m/s².
- Use `GeneralPurposeIO\Contracts\Circuits\Attributes\*` — not Fabricate Circuits attributes.
- SPI factory/profile params: `spi_device`, `chip_select`, `spi_adapter` — not bare `device`/`adapter`. LIS3DH SPI is mode 0 @ 500 kHz.
- String-backed catalog/console enums; int-backed register/rate/range enums. Cases FULLY UPPERCASE. Prefer `is_null()`.
- Requires leaf components (not kitchen-sink frameworks): `fabricate/nuts-and-bolts`, `gpio/circuits`, `gpio/contracts`, `gpio/digital`, `gpio/i2c`, `gpio/spi`, `waveforms/contracts`.
