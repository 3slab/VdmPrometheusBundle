<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Vdm\Bundle\PrometheusBundle\Tests\PrometheusKernelTestCase;

class CustomStoragePassInvalidServiceTest extends PrometheusKernelTestCase
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
        // Override because we want to setup in test
    }

    /**
     * {@inheritDoc}
     */
    protected static function getAppName(): string
    {
        return 'custom-invalid-service';
    }

    public function testInvalidServiceWithCustomStorage()
    {
        $this->expectException(ServiceNotFoundException::class);

        parent::setUp();
    }
}
