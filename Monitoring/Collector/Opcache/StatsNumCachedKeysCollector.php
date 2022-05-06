<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsNumCachedKeysCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_num_cached_keys';
    public const COLLECTOR_DESCRIPTION = 'stats_num_cached_keys gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['num_cached_keys'];
        }
    }
}
