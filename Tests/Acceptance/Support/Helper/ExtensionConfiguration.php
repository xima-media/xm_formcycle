<?php

namespace Xima\XmFormcycle\Tests\Acceptance\Support\Helper;

use Codeception\Module;
use Composer\InstalledVersions;
use ReflectionClass;

final class ExtensionConfiguration
{
    /**
     * @var non-empty-string
     */
    private readonly string $scriptPath;

    public function __construct(
        private readonly Module\Asserts $asserts,
        private readonly Module\Cli $cli,
    ) {
        $this->scriptPath = $this->determineScriptPath();
    }

    /**
     * @return non-empty-string
     */
    private function determineScriptPath(): string
    {
        $buildDir = \dirname(self::getVendorDirectory());

        if (file_exists(__DIR__ . '/../../../../vendor/bin/typo3cms')) {
            return $buildDir . '/vendor/bin/typo3cms';
        }

        return $buildDir . '/vendor/bin/typo3';
    }

    private static function getVendorDirectory(): string
    {
        $reflectionClass = new ReflectionClass(InstalledVersions::class);
        $filename = $reflectionClass->getFileName();

        if (false === $filename) {
            throw new \RuntimeException('Vendor directory cannot be determined', 1709887555);
        }

        return dirname($filename, 2);
    }

    public function read(string $path): mixed
    {
        $command = $this->buildCommand(['configuration:showactive', 'EXTENSIONS/xm_formcycle/' . $path, '--json']);

        $this->cli->runShellCommand($command);

        $output = $this->cli->grabShellOutput();

        $this->asserts->assertJson($output);

        return json_decode($output, true);
    }

    /**
     * @param non-empty-list<scalar> $command
     * @return non-empty-string
     */
    private function buildCommand(array $command): string
    {
        $fullCommand = [$this->scriptPath, ...$command];
        $fullCommand = array_map('strval', $fullCommand);

        return implode(' ', array_map('escapeshellarg', $fullCommand));
    }

    /**
     * @param non-empty-string $path
     */
    public function write(string $path, mixed $value): void
    {
        $command = $this->buildCommand(['configuration:set', 'EXTENSIONS/xm_formcycle/' . $path, $value]);

        $this->cli->runShellCommand($command);
    }
}
