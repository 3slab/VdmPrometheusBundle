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
        dump($client->getResponse());
    }
}
