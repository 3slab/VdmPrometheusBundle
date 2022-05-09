<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Opcache;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache\StatsLastRestartTimeCollector;

class StatsLastRestartTimeCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new StatsLastRestartTimeCollector();
        $this->assertEquals(StatsLastRestartTimeCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new StatsLastRestartTimeCollector();
        $this->assertEquals(
            StatsLastRestartTimeCollector::COLLECTOR_DESCRIPTION,
            $collector->getCollectorDescription()
        );
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new StatsLastRestartTimeCollector();
        $collector->collect($mockRequest, $mockResponse);

        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status(false);
            if ($status['opcache_enabled']) {
                $this->assertThat($collector->getData(), $this->isType('int'));
            } else {
                $this->assertThat($collector->getData(), $this->isNull());
            }
        } else {
            $this->assertThat($collector->getData(), $this->isNull());
        }
    }
}
