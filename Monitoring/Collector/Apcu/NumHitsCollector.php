<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NumHitsCollector extends AbstractApcuCollector
{
    public const COLLECTOR_NAME = 'num_hits';
    public const COLLECTOR_DESCRIPTION = 'num_hits gauge';

    public function collect(Request $request, Response $response)
    {
        $cacheInfo = $this->getApcuCacheInfo();

        if (!empty($cacheInfo)) {
            $this->data = $cacheInfo['num_hits'];
        }
    }
}
