<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseCodeCollector;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseSizeCollector;

class ResponseSizeCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new ResponseSizeCollector('vdm');
        $this->assertEquals(ResponseSizeCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new ResponseSizeCollector('vdm');
        $this->assertEquals(ResponseSizeCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');

        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');
        $mockResponse->expects($this->once())->method('getContent')->willReturn('1234');

        $collector = new ResponseSizeCollector('vdm');
        $collector->collect($mockRequest, $mockResponse);

        $this->assertEquals(
            4,
            $collector->getData()
        );
    }
}
