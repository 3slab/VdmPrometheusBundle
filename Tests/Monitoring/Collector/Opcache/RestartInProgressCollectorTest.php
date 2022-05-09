<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Opcache;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache\RestartInProgressCollector;

class RestartInProgressCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new RestartInProgressCollector();
        $this->assertEquals(RestartInProgressCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new RestartInProgressCollector();
        $this->assertEquals(RestartInProgressCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new RestartInProgressCollector();
        $collector->collect($mockRequest, $mockResponse);

        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status(false);
            if ($status['opcache_enabled']) {
                $this->assertThat($collector->getData(), $this->equalTo($status['restart_in_progress'] ? 1 : 0));
            } else {
                $this->assertThat($collector->getData(), $this->isNull());
            }
        } else {
            $this->assertThat($collector->getData(), $this->isNull());
        }
    }
}
