<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Opcache;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache\InternedStringsCountCollector;

class InternedStringsCountCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new InternedStringsCountCollector();
        $this->assertEquals(InternedStringsCountCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new InternedStringsCountCollector();
        $this->assertEquals(
            InternedStringsCountCollector::COLLECTOR_DESCRIPTION,
            $collector->getCollectorDescription()
        );
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new InternedStringsCountCollector();
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
