<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Controller;

use Vdm\Bundle\PrometheusBundle\Tests\PrometheusKernelTestCase;

class PrometheusControllerTest extends PrometheusKernelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected static function getAppName(): string
    {
        return 'in-memory';
    }

    public function testMetricRoute()
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request('GET', '/myapp/success');
        $client->request('GET', '/metrics');

        $this->assertRegExp(
            "/# HELP vdm_sf_app_response_time Request execution time in seconds\n" .
            "# TYPE vdm_sf_app_response_time gauge\n" .
            "vdm_sf_app_response_time\\{app=\"app\",route=\"success_route\"\\} " . $this->getFloatRegex() . "\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_response_size Response size in bytes\n" .
            "# TYPE vdm_sf_app_response_size gauge\n" .
            "vdm_sf_app_response_size\\{app=\"app\",route=\"success_route\"\\} 7\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_response_code_total Number of call to the API per response code\n" .
            "# TYPE vdm_sf_app_response_code_total counter\n" .
            "vdm_sf_app_response_code_total\\{app=\"app\",route=\"success_route\",http_code=\"200\"\\} 1\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_memory_usage Memory in byte per route\n" .
                "# TYPE vdm_sf_app_memory_usage gauge\n" .
                "vdm_sf_app_memory_usage\\{app=\"app\",route=\"success_route\"\\} \d+\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_call_total Number of call to the app\n" .
            "# TYPE vdm_sf_app_call_total counter\n" .
            "vdm_sf_app_call_total\\{app=\"app\",route=\"success_route\"\\} 1\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP php_info Information about the PHP environment.\n" .
            "# TYPE php_info gauge\n" .
            "php_info{version=\"" . PHP_VERSION . "\"} 1\n/",
            $client->getResponse()->getContent()
        );
    }

    public function testMetricRouteMultipleCalls()
    {
        $client = static::createClient();
        $client->disableReboot();

        $client->request('GET', '/myapp/success');
        $client->request('GET', '/myapp/error');
        $client->request('GET', '/myapp/success');
        $client->request('GET', '/myapp/unknown');
        $client->request('GET', '/metrics');

        $this->assertRegExp(
            "/# HELP vdm_sf_app_response_time Request execution time in seconds\n" .
            "# TYPE vdm_sf_app_response_time gauge\n" .
            "vdm_sf_app_response_time\\{app=\"app\",route=\"\"\\} " . $this->getFloatRegex() . "\n" .
            "vdm_sf_app_response_time\\{app=\"app\",route=\"error_route\"\\} " . $this->getFloatRegex() . "\n" .
            "vdm_sf_app_response_time\\{app=\"app\",route=\"success_route\"\\} " . $this->getFloatRegex() . "\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_response_size Response size in bytes\n" .
            "# TYPE vdm_sf_app_response_size gauge\n" .
            "vdm_sf_app_response_size\\{app=\"app\",route=\"\"} \d+\n" .
            "vdm_sf_app_response_size\\{app=\"app\",route=\"error_route\"\\} 5\n" .
            "vdm_sf_app_response_size\\{app=\"app\",route=\"success_route\"\\} 7\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_response_code_total Number of call to the API per response code\n" .
            "# TYPE vdm_sf_app_response_code_total counter\n" .
            "vdm_sf_app_response_code_total\\{app=\"app\",route=\"\",http_code=\"404\"\\} 1\n" .
            "vdm_sf_app_response_code_total\\{app=\"app\",route=\"error_route\",http_code=\"500\"\\} 1\n" .
            "vdm_sf_app_response_code_total\\{app=\"app\",route=\"success_route\",http_code=\"200\"\\} 2\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_memory_usage Memory in byte per route\n" .
            "# TYPE vdm_sf_app_memory_usage gauge\n" .
            "vdm_sf_app_memory_usage\\{app=\"app\",route=\"\"\\} \d+\n" .
            "vdm_sf_app_memory_usage\\{app=\"app\",route=\"error_route\"\\} \d+\n" .
            "vdm_sf_app_memory_usage\\{app=\"app\",route=\"success_route\"\\} \d+\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP vdm_sf_app_call_total Number of call to the app\n" .
            "# TYPE vdm_sf_app_call_total counter\n" .
            "vdm_sf_app_call_total\\{app=\"app\",route=\"\"\\} 1\n" .
            "vdm_sf_app_call_total\\{app=\"app\",route=\"error_route\"\\} 1\n" .
            "vdm_sf_app_call_total\\{app=\"app\",route=\"success_route\"\\} 2\n/",
            $client->getResponse()->getContent()
        );

        $this->assertRegExp(
            "/# HELP php_info Information about the PHP environment.\n" .
            "# TYPE php_info gauge\n" .
            "php_info{version=\"" . PHP_VERSION . "\"} 1\n/",
            $client->getResponse()->getContent()
        );
    }
}
