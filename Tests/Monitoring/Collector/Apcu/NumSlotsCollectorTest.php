<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring\Collector\Apcu;

use PHPUnit\Framework\TestCase;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu\NumSlotsCollector;

class NumSlotsCollectorTest extends TestCase
{
    public function testGetCollectorName()
    {
        $collector = new NumSlotsCollector();
        $this->assertEquals(NumSlotsCollector::COLLECTOR_NAME, $collector->getCollectorName());
    }

    public function testGetCollectorDescription()
    {
        $collector = new NumSlotsCollector();
        $this->assertEquals(NumSlotsCollector::COLLECTOR_DESCRIPTION, $collector->getCollectorDescription());
    }

    public function testCollect()
    {
        $mockRequest = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $mockResponse = $this->createMock('Symfony\Component\HttpFoundation\Response');

        $apcuCacheInfoExists = function_exists('apcu_cache_info');

        $collector = new NumSlotsCollector();
        $collector->collect($mockRequest, $mockResponse);

        if ($apcuCacheInfoExists) {
            $this->assertThat($collector->getData(), $this->isType('int'));
        } else {
            $this->assertThat($collector->getData(), $this->isNull());
        }
    }
}
