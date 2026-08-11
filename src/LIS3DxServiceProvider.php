<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\Console\LIS3DxMakeProfileCommand;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\Enums\LIS3DxCatalogIc;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\Enums\LIS3DxConsoleCommand;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DH\LIS3DH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\LIS3DSH\LIS3DSH;
use DeptOfScrapyardRobotics\Sensors\LIS3Dx\Sketches\LIS3DxSmoke;
use Fabricate\Contracts\Sketches\SketchRegistry;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\Circuit;

class LIS3DxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(LIS3DxMakeProfileCommand::class);
        $this->commands([
            LIS3DxMakeProfileCommand::class,
        ]);
    }

    public function boot(): void
    {
        Circuit::addCircuit(LIS3DxCatalogIc::LIS3DH->value, LIS3DH::class);
        Circuit::addCircuit(LIS3DxCatalogIc::LIS3DSH->value, LIS3DSH::class);

        $maker = LIS3DxConsoleCommand::MAKE_PROFILE->value;
        foreach (LIS3DxCatalogIc::cases() as $ic) {
            Circuit::registerProfileCommand($ic->value, $maker);
        }

        $this->registerSketch();
    }

    protected function registerSketch(): void
    {
        if (! $this->container->bound(SketchRegistry::class)) {
            return;
        }

        /** @var SketchRegistry $registry */
        $registry = $this->container->make(SketchRegistry::class);

        if (! $registry->has('lis3dx-smoke')) {
            $registry->registerConvention('lis3dx-smoke', LIS3DxSmoke::class);
        }
    }
}
