<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Opcache;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache\StatsHashRestartsCollector;

class StatsHashRestartsCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new StatsHashRestartsCollector();
        $this->assertEquals(StatsHashRestartsCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new StatsHashRestartsCollector();
        $this->assertEquals(StatsHashRestartsCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $opcacheGetStatusExists = function_exists('opcache_get_status');

        $collector = new StatsHashRestartsCollector();
        $collector->collect($mockRequest, $mockResponse);

        if ($opcacheGetStatusExists) {
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
