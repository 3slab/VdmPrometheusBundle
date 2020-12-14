<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Vdm\Bundle\PrometheusBundle\EventSubscriber\MonitoringSubscriber;

class MonitoringSubscriberTest extends TestCase
{
    public function testOnKernelTerminate()
    {
        $mockCollector = $this->createMock('Vdm\Bundle\PrometheusBundle\Monitoring\CollectorRegistry');
        $mockCollector->expects($this->once())->method('save');

        $mockLogger = $this->createMock('Psr\Log\LoggerInterface');

        $mockKernel = $this->createMock('Symfony\Component\HttpKernel\HttpKernelInterface');

        $subscriber = new MonitoringSubscriber($mockCollector, $mockLogger);
        $subscriber->onKernelTerminate(new TerminateEvent($mockKernel, new Request(), new Response()));
    }

    public function testOnKernelResponseNotMaster()
    {
        $mockCollector = $this->createMock('Vdm\Bundle\PrometheusBundle\Monitoring\CollectorRegistry');
        $mockCollector->expects($this->never())->method('collect');

        $mockLogger = $this->createMock('Psr\Log\LoggerInterface');

        $mockKernel = $this->createMock('Symfony\Component\HttpKernel\HttpKernelInterface');

        $subscriber = new MonitoringSubscriber($mockCollector, $mockLogger);
        $subscriber->onKernelResponse(
            new ResponseEvent(
                $mockKernel,
                new Request(),
                HttpKernelInterface::SUB_REQUEST,
                new Response()
            )
        );
    }
}
