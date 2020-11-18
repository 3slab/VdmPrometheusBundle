<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\DependencyInjection\Compiler;

use Prometheus\CollectorRegistry;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Vdm\Bundle\PrometheusBundle\Tests\PrometheusKernelTestCase;

class CustomStoragePassValidServiceTest extends PrometheusKernelTestCase
{
    /**
     * {@inheritDoc}
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * {@inheritDoc}
     */
    protected static function getAppName(): string
    {
        return 'custom';
    }

    public function testValidServiceWithCustomStorage()
    {
        /** @var CollectorRegistry $prometheus */
        $prometheus = self::$kernel->getContainer()->get('vdm_prometheus_registry');

        $this->assertEquals(
            ['custom' => 'correctly instanciated'],
            $prometheus->getMetricFamilySamples()
        );
    }
}
