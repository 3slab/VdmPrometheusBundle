<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Apcu;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu\TtlCollector;

class TtlCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new TtlCollector();
        $this->assertEquals(TtlCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new TtlCollector();
        $this->assertEquals(TtlCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $apcuCacheInfoExists = function_exists('apcu_cache_info');

        $collector = new TtlCollector();
        $collector->collect($mockRequest, $mockResponse);

        if ($apcuCacheInfoExists) {
            $this->assertThat($collector->getData(), $this->isType('int'));
        } else {
            $this->assertThat($collector->getData(), $this->isNull());
        }
    }
}
