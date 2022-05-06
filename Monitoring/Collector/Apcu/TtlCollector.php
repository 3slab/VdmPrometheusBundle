<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TtlCollector extends AbstractApcuCollector
{
    public const COLLECTOR_NAME = 'ttl';
    public const COLLECTOR_DESCRIPTION = 'ttl gauge';

    public function collect(Request $request, Response $response)
    {
        $cacheInfo = $this->getApcuCacheInfo();

        if (!empty($cacheInfo)) {
            $this->data = $cacheInfo['ttl'];
        }
    }
}
