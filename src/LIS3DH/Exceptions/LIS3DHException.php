<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\Exceptions;

use RuntimeException;

class LIS3DHException extends RuntimeException
{
    public static function invalidProperty(string $name): static
    {
        return new static("Invalid property $name");
    }

    public static function invalidChipId(int $chip_id): static
    {
        return new static(sprintf('Invalid LIS3DH Chip ID — expected 0x33, got 0x%02X', $chip_id));
    }
}
