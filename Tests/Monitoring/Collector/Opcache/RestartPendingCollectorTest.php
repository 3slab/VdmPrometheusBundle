<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Opcache;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache\RestartPendingCollector;

class RestartPendingCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new RestartPendingCollector();
        $this->assertEquals(RestartPendingCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new RestartPendingCollector();
        $this->assertEquals(RestartPendingCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $opcacheGetStatusExists = function_exists('opcache_get_status');

        $collector = new RestartPendingCollector();
        $collector->collect($mockRequest, $mockResponse);

        if ($opcacheGetStatusExists) {
            $status = opcache_get_status(false);
            if ($status['opcache_enabled']) {
                $this->assertThat($collector->getData(), $this->equalTo($status['restart_pending'] ? 1 : 0));
            } else {
                $this->assertThat($collector->getData(), $this->isNull());
            }
        } else {
            $this->assertThat($collector->getData(), $this->isNull());
        }
    }
}
