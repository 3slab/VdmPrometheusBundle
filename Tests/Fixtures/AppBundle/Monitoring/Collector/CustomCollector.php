<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Fixtures\AppBundle\Monitoring\Collector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\AbstractCollector;

class CustomCollector extends AbstractCollector
{
    public function collect(Request $request, Response $response)
    {
        $this->data = 10;
    }

    public function getCollectorName(): string
    {
        return 'my_custom_metric';
    }

    public function getCollectorDescription(): string
    {
        return 'Custom metric used in unit tests';
    }
}
