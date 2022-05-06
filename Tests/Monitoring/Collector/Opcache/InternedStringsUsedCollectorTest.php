<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Opcache;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache\InternedStringsUsedCollector;

class InternedStringsUsedCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new InternedStringsUsedCollector();
        $this->assertEquals(InternedStringsUsedCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new InternedStringsUsedCollector();
        $this->assertEquals(InternedStringsUsedCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $opcacheGetStatusExists = function_exists('opcache_get_status');

        $collector = new InternedStringsUsedCollector();
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
