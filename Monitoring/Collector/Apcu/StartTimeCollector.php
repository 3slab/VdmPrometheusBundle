<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StartTimeCollector extends AbstractApcuCollector
{
    public const COLLECTOR_NAME = 'start_time';
    public const COLLECTOR_DESCRIPTION = 'start_time gauge';

    public function collect(Request $request, Response $response)
    {
        $cacheInfo = $this->getApcuCacheInfo();

        if (!empty($cacheInfo)) {
            $this->data = $cacheInfo['start_time'];
        }
    }
}
