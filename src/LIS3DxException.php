<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx;

use GeneralPurposeIO\Contracts\Circuits\CircuitException;

class LIS3DxException extends CircuitException
{
    public static function transportMissingProtocol(): static
    {
        return new static('LIS3Dx devices require an SPI or an I2C capable connection.');
    }

    public static function invalidChipId(int $chip_id, int $expected_id): static
    {
        return new static("Invalid LIS3Dx Device Chip ID — expected {$expected_id}, got {$chip_id}");
    }

    public static function invalidProperty(string $name, string $class): static
    {
        return new static("Invalid property [{$name}] on {$class}");
    }

    public static function notImplemented(string $feature): static
    {
        return new static("LIS3Dx feature not implemented yet: {$feature}");
    }
}
