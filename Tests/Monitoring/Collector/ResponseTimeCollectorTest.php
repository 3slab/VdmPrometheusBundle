<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\MemoryCollector;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseTimeCollector;

class ResponseTimeCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        /** @var KernelInterface $mockKernel */
        $mockKernel = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\KernelInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $collector = new ResponseTimeCollector('vdm', $mockKernel);
        $this->assertEquals(ResponseTimeCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        /** @var KernelInterface $mockKernel */
        $mockKernel = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\KernelInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $collector = new ResponseTimeCollector('vdm', $mockKernel);
        $this->assertEquals(ResponseTimeCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    /**
     * @param $kernelStartTime
     * @param $startTime
     *
     * @dataProvider collectWithoutKernelStartTimeProvider
     */
    public function testCollect($kernelStartTime, $startTime)
    {
        $mockKernel = $this->createMock('Symfony\Component\HttpKernel\KernelInterface');
        $mockKernel->expects($this->once())->method('getStartTime')->willReturn($kernelStartTime);

        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockRequest->server = new ParameterBag(['REQUEST_TIME_FLOAT' => $startTime]);

        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new ResponseTimeCollector('vdm', $mockKernel);
        $collector->collect($mockRequest, $mockResponse);

        $this->assertThat(
            $collector->getData(),
            $this->logicalAnd(
                $this->greaterThan(0),
                $this->lessThanOrEqual((microtime(true) - $startTime) * 1000)
            )
        );
    }

    public function collectWithoutKernelStartTimeProvider()
    {
        return [
            [-INF, microtime(true)],
            [null, microtime(true)],
            [microtime(true), microtime(true)]
        ];
    }
}
