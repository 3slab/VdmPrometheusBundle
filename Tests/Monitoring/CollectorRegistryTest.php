<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Monitoring;

use PHPUnit\Framework\TestCase;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseCodeCollector;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\ResponseSizeCollector;
use Vdm\Bundle\PrometheusBundle\Monitoring\CollectorRegistry;

class CollectorRegistryTest extends TestCase
{
    public const TEST_NAMESPACE = 'mynamespace';
    public $phpVersion = PHP_VERSION;

    public function testSave()
    {
        $registry = new \Prometheus\CollectorRegistry(new InMemory());
        $collectorRegistry = new CollectorRegistry('myapp', static::TEST_NAMESPACE, $registry);
        $collectorRegistry->setCurrentRoute('myroute');
        $collectorRegistry->addCollector(new ResponseSizeCollector(static::TEST_NAMESPACE));
        $collectorRegistry->addCollector(new ResponseCodeCollector(static::TEST_NAMESPACE));

        $collectorRegistry->collect(new Request(), new Response('1234', 200));

        $collectorRegistry->save();

        $renderer = new RenderTextFormat();
        $result = $renderer->render($registry->getMetricFamilySamples());

        $expectedResult = <<<EXPECTEDRESULT
# HELP mynamespace_sf_app_response_code_total Number of call to the API per response code
# TYPE mynamespace_sf_app_response_code_total counter
mynamespace_sf_app_response_code_total{app="myapp",route="myroute",http_code="200"} 1
# HELP mynamespace_sf_app_response_size Response size in bytes
# TYPE mynamespace_sf_app_response_size gauge
mynamespace_sf_app_response_size{app="myapp",route="myroute"} 4
# HELP php_info Information about the PHP environment.
# TYPE php_info gauge
php_info{version="{$this->phpVersion}"} 1

EXPECTEDRESULT;

        $this->assertEquals(
            $expectedResult,
            $result
        );
    }

    public function testSaveWithoutDefaultMetrics()
    {
        $registry = new \Prometheus\CollectorRegistry(new InMemory(), false);
        $collectorRegistry = new CollectorRegistry('myapp', static::TEST_NAMESPACE, $registry);
        $collectorRegistry->setCurrentRoute('myroute');
        $collectorRegistry->addCollector(new ResponseSizeCollector(static::TEST_NAMESPACE));
        $collectorRegistry->addCollector(new ResponseCodeCollector(static::TEST_NAMESPACE));

        $collectorRegistry->collect(new Request(), new Response('1234', 200));

        $collectorRegistry->save();

        $renderer = new RenderTextFormat();
        $result = $renderer->render($registry->getMetricFamilySamples());

        $expectedResult = <<<EXPECTEDRESULT
# HELP mynamespace_sf_app_response_code_total Number of call to the API per response code
# TYPE mynamespace_sf_app_response_code_total counter
mynamespace_sf_app_response_code_total{app="myapp",route="myroute",http_code="200"} 1
# HELP mynamespace_sf_app_response_size Response size in bytes
# TYPE mynamespace_sf_app_response_size gauge
mynamespace_sf_app_response_size{app="myapp",route="myroute"} 4

EXPECTEDRESULT;

        $this->assertEquals(
            $expectedResult,
            $result
        );
    }
}
