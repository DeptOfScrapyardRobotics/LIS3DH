<?php

namespace DeptOfScrapyardRobotics\Sensors\LIS3Dx\Sketches;

use DeptOfScrapyardRobotics\Sensors\LIS3Dx\Enums\LIS3DxCatalogIc;
use Fabricate\Contracts\Sketches\Attributes\Sketch as SketchAttribute;
use Fabricate\Contracts\Sketches\SketchLoopResult;
use Fabricate\Sketches\Sketch;
use GeneralPurposeIO\Circuits\Types\SensorIC;
use GeneralPurposeIO\Contracts\Circuits\IntegratedCircuit;
use GeneralPurposeIO\Core\MagicAliases\Circuit;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

#[SketchAttribute('lis3dx-smoke')]
class LIS3DxSmoke extends Sketch
{
    protected string $description = 'Smoke-test a provisioned LIS3Dx profile (Ctrl-C to end)';

    protected ?IntegratedCircuit $sensor = null;

    protected ?string $profileName = null;

    protected bool $stopRequested = false;

    protected bool $announced = false;

    protected int $lastSampleNs = 0;

    public function configureCommand(Command $command): void
    {
        $command->addOption(
            'profile',
            null,
            InputOption::VALUE_OPTIONAL,
            'circuits.php profile name (ic must be lis3dh/lis3dsh)',
        );
    }

    public function boot(): void
    {
        if (extension_loaded('pcntl')) {
            pcntl_async_signals(true);
            $stop = function (): void {
                $this->stopRequested = true;
            };
            pcntl_signal(SIGINT, $stop);
            pcntl_signal(SIGTERM, $stop);
        }

        $profiles = $this->lis3Profiles();
        if ($profiles === []) {
            $this->error('No LIS3Dx profiles in config/circuits.php. Run: php workshop lis3dx:make-profile');

            return;
        }

        $requested = $this->option('profile');
        if (is_string($requested) && $requested !== '') {
            if (! isset($profiles[$requested])) {
                $this->error("Profile [{$requested}] is missing or not a LIS3Dx ic.");

                return;
            }
            $this->profileName = $requested;
        } elseif (count($profiles) === 1) {
            $this->profileName = array_key_first($profiles);
        } else {
            $this->profileName = $this->choice('Which LIS3Dx profile?', array_keys($profiles));
        }

        try {
            $this->sensor = Circuit::profile($this->profileName);
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            $this->sensor = null;
        }
    }

    public function loop(): SketchLoopResult
    {
        if ($this->stopRequested) {
            $this->info('LIS3Dx smoke stopped.');

            return SketchLoopResult::STOP;
        }

        if (is_null($this->sensor) || is_null($this->profileName)) {
            return SketchLoopResult::STOP;
        }

        if (! $this->announced) {
            $ic = (string) (config("circuits.{$this->profileName}.ic") ?? 'lis3dx');
            $this->info("LIS3Dx smoke via Circuit::profile('{$this->profileName}') [{$ic}]");
            $this->line('  Sampling X/Y/Z g — Ctrl-C to end.');
            $this->announced = true;
        }

        $now = hrtime(true);
        if ($this->lastSampleNs !== 0 && ($now - $this->lastSampleNs) < 250_000_000) {
            usleep(10_000);

            return SketchLoopResult::CONTINUE;
        }

        try {
            if (! method_exists($this->sensor, 'x') || ! method_exists($this->sensor, 'y') || ! method_exists($this->sensor, 'z')) {
                $this->error('Resolved IC does not expose x()/y()/z().');

                return SketchLoopResult::STOP;
            }

            $this->line(sprintf(
                '  X=%+.3fg  Y=%+.3fg  Z=%+.3fg',
                $this->sensor->x(),
                $this->sensor->y(),
                $this->sensor->z(),
            ));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return SketchLoopResult::STOP;
        }

        $this->lastSampleNs = $now;

        return SketchLoopResult::CONTINUE;
    }

    public function shutdown(): void
    {
        if ($this->sensor instanceof SensorIC || $this->sensor instanceof IntegratedCircuit) {
            try {
                $this->sensor->close();
            } catch (Throwable) {
                //
            }
        }
        $this->sensor = null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function lis3Profiles(): array
    {
        $all = config('circuits', []);
        if (! is_array($all)) {
            return [];
        }

        $matched = [];
        foreach ($all as $name => $recipe) {
            if (! is_string($name) || ! is_array($recipe)) {
                continue;
            }
            $ic = $recipe['ic'] ?? null;
            if (is_string($ic) && ! is_null(LIS3DxCatalogIc::tryFrom($ic))) {
                $matched[$name] = $recipe;
            }
        }

        return $matched;
    }
}
