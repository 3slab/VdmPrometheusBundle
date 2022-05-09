<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Apcu;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu\NumHitsCollector;

class NumHitsCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new NumHitsCollector();
        $this->assertEquals(NumHitsCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new NumHitsCollector();
        $this->assertEquals(NumHitsCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $collector = new NumHitsCollector();
        $collector->collect($mockRequest, $mockResponse);

        if (function_exists('apcu_enabled')) {
            if (apcu_enabled() && function_exists('apcu_cache_info')) {
                $this->assertThat($collector->getData(), $this->isType('int'));
            } else {
                $this->assertThat($collector->getData(), $this->isNull());
            }
        } else {
            $this->assertThat($collector->getData(), $this->isNull());
        }
    }
}
