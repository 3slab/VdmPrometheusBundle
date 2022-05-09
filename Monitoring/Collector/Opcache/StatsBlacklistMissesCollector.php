<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsBlacklistMissesCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_blacklist_misses';
    public const COLLECTOR_DESCRIPTION = 'stats_blacklist_misses gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['blacklist_misses'];
        }
    }
}
