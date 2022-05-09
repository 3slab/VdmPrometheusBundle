<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsMaxCachedKeysCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_max_cached_keys';
    public const COLLECTOR_DESCRIPTION = 'stats_max_cached_keys gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['max_cached_keys'];
        }
    }
}
