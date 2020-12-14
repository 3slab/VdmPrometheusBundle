<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Controller;

use Vdm\Bundle\PrometheusBundle\Tests\PrometheusKernelTestCase;

class PrometheusControllerFullConfigTest extends PrometheusKernelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected static function getAppName(): string
    {
        return 'in-memory-fullconfig';
    }

    public function testMetricRouteWrongPath()
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request('GET', '/metrics');

        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testMetricRouteWithoutSecret()
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request('GET', '/mycustommetrics');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals(
            '',
            $client->getResponse()->getContent()
        );
    }

    public function testMetricRouteWitSecretInUrl()
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request('GET', '/myapp/success');
        $client->request('GET', '/myapp/error');
        $client->request('GET', '/myapp/success');
        $client->request('GET', '/myapp/unknown');
        $client->request('GET', '/mycustommetrics?secret=mysecret');

        $this->assertMetricResponse($client->getResponse());
    }

    public function testMetricRouteWitSecretInHeader()
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request('GET', '/myapp/success');
        $client->request('GET', '/myapp/error');
        $client->request('GET', '/myapp/success');
        $client->request('GET', '/myapp/unknown');
        $client->request('GET', '/mycustommetrics', [], [], ['HTTP_VDM_PROMETHEUS_SECRET' => 'mysecret']);

        $this->assertMetricResponse($client->getResponse());
    }

    public function assertMetricResponse($response)
    {
        $this->assertEquals(
            200,
            $response->getStatusCode()
        );

        $this->assertRegExp(
            "/# HELP mycustomnamespace_sf_app_response_time Request execution time in seconds\n" .
            "# TYPE mycustomnamespace_sf_app_response_time gauge\n" .
            "mycustomnamespace_sf_app_response_time\\{app=\"myapp\",route=\"\"\\} " .
            $this->getFloatRegex() . "\n" .
            "mycustomnamespace_sf_app_response_time\\{app=\"myapp\",route=\"error_route\"\\} " .
            $this->getFloatRegex() . "\n" .
            "mycustomnamespace_sf_app_response_time\\{app=\"myapp\",route=\"success_route\"\\} " .
            $this->getFloatRegex() . "\n/",
            $response->getContent()
        );

        $this->assertRegExp(
            "/# HELP mycustomnamespace_sf_app_response_size Response size in bytes\n" .
            "# TYPE mycustomnamespace_sf_app_response_size gauge\n" .
            "mycustomnamespace_sf_app_response_size\\{app=\"myapp\",route=\"\"} \d+\n" .
            "mycustomnamespace_sf_app_response_size\\{app=\"myapp\",route=\"error_route\"\\} 5\n" .
            "mycustomnamespace_sf_app_response_size\\{app=\"myapp\",route=\"success_route\"\\} 7\n/",
            $response->getContent()
        );

        $this->assertRegExp(
            "/# HELP mycustomnamespace_sf_app_response_code_total Number of call to the API per response" .
            " code\n" .
            "# TYPE mycustomnamespace_sf_app_response_code_total counter\n" .
            "mycustomnamespace_sf_app_response_code_total\\{app=\"myapp\",route=\"\",http_code=\"404\"\\} " .
            "1\n" .
            "mycustomnamespace_sf_app_response_code_total\\{app=\"myapp\",route=\"error_route\",http_code=\"500\"\\} " .
            "1\n" .
            "mycustomnamespace_sf_app_response_code_total\\{app=\"myapp\",route=\"success_route\",http_code=\"200\"" .
            "\\} 2\n/",
            $response->getContent()
        );

        $this->assertRegExp(
            "/# HELP mycustomnamespace_sf_app_memory_usage Memory in byte per route\n" .
            "# TYPE mycustomnamespace_sf_app_memory_usage gauge\n" .
            "mycustomnamespace_sf_app_memory_usage\\{app=\"myapp\",route=\"\"\\} \d+\n" .
            "mycustomnamespace_sf_app_memory_usage\\{app=\"myapp\",route=\"error_route\"\\} \d+\n" .
            "mycustomnamespace_sf_app_memory_usage\\{app=\"myapp\",route=\"success_route\"\\} \d+\n/",
            $response->getContent()
        );

        $this->assertRegExp(
            "/# HELP mycustomnamespace_sf_app_call_total Number of call to the app\n" .
            "# TYPE mycustomnamespace_sf_app_call_total counter\n" .
            "mycustomnamespace_sf_app_call_total\\{app=\"myapp\",route=\"\"\\} 1\n" .
            "mycustomnamespace_sf_app_call_total\\{app=\"myapp\",route=\"error_route\"\\} 1\n" .
            "mycustomnamespace_sf_app_call_total\\{app=\"myapp\",route=\"success_route\"\\} 2\n/",
            $response->getContent()
        );

        $this->assertStringNotContainsString(
            "TYPE php_info gauge",
            $response->getContent()
        );
    }
}
