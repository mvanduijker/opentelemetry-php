<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\Unit\SDK\Metrics;

use AssertWell\PHPUnitGlobalState\EnvironmentVariables;
use OpenTelemetry\API\Behavior\Internal\Logging;
use OpenTelemetry\API\Metrics\MeterInterface;
use OpenTelemetry\SDK\Common\Configuration\KnownValues;
use OpenTelemetry\SDK\Common\Configuration\Variables;
use OpenTelemetry\SDK\Metrics\MeterProviderFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OpenTelemetry\SDK\Metrics\MeterProviderFactory
 */
class MeterProviderFactoryTest extends TestCase
{
    use EnvironmentVariables;

    public function setUp(): void
    {
        Logging::disable();
    }

    public function tearDown(): void
    {
        $this->restoreEnvironmentVariables();
    }

    /**
     * @dataProvider exporterProvider
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function test_create(string $exporter): void
    {
        $_SERVER[Variables::OTEL_METRICS_EXPORTER] = $exporter;
        $provider = (new MeterProviderFactory())->create();
        $this->assertInstanceOf(MeterInterface::class, $provider->getMeter('test'));
    }

    public static function exporterProvider(): array
    {
        return [
            'otlp' => [KnownValues::VALUE_OTLP],
            'none' => [KnownValues::VALUE_NONE],
            'unimplemented' => ['foo'],
        ];
    }
}
