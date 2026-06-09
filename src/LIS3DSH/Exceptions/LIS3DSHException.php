<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\Exceptions;

use RuntimeException;

class LIS3DSHException extends RuntimeException
{
    public static function invalidProperty(string $name): static
    {
        return new static("Invalid property $name");
    }

    public static function invalidChipId(int $chip_id): static
    {
        return new static(sprintf('Invalid LIS3DSH Chip ID — expected 0x3F, got 0x%02X', $chip_id));
    }
}
