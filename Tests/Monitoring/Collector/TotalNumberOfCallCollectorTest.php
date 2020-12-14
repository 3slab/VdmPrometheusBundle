<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseCodeCollector;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\TotalNumberOfCallCollector;

class TotalNumberOfCallCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new TotalNumberOfCallCollector('vdm');
        $this->assertEquals(TotalNumberOfCallCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new TotalNumberOfCallCollector('vdm');
        $this->assertEquals(TotalNumberOfCallCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new TotalNumberOfCallCollector('vdm');
        $collector->collect($mockRequest, $mockResponse);

        $this->assertNull(
            $collector->getData()
        );
    }
}
