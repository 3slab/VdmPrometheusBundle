<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FullCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'full';
    public const COLLECTOR_DESCRIPTION = 'full gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['cache_full'] ? 1 : 0;
        }
    }
}
