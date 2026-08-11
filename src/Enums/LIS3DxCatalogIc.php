<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\Enums;

enum LIS3DxCatalogIc: string
{
    case LIS3DH = 'lis3dh';
    case LIS3DSH = 'lis3dsh';

    /**
     * @return list<string>
     */
    public static function slugs(): array
    {
        return array_map(
            static fn (self $case): string => $case->value,
            self::cases(),
        );
    }
}
