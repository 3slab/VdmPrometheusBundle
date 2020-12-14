<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseCodeCollector;

class ResponseCodeCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new ResponseCodeCollector('vdm');
        $this->assertEquals(ResponseCodeCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new ResponseCodeCollector('vdm');
        $this->assertEquals(ResponseCodeCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');

        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');
        $mockResponse->expects($this->once())->method('getStatusCode')->willReturn(200);

        $collector = new ResponseCodeCollector('vdm');
        $collector->collect($mockRequest, $mockResponse);

        $this->assertEquals(
            200,
            $collector->getData()
        );
    }
}
