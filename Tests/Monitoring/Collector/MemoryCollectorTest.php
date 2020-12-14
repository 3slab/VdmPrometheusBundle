<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\MemoryCollector;

class MemoryCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new MemoryCollector('vdm');
        $this->assertEquals(MemoryCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new MemoryCollector('vdm');
        $this->assertEquals(MemoryCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new MemoryCollector('vdm');
        $collector->collect($mockRequest, $mockResponse);

        $memory = memory_get_peak_usage(true);
        $memoryMin = $memory - 10000;
        $memoryMax = $memory + 10000;

        $this->assertThat(
            $collector->getData(),
            $this->logicalAnd(
                $this->greaterThan($memoryMin),
                $this->lessThan($memoryMax)
            )
        );
    }
}
