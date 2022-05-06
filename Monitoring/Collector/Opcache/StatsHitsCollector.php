<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsHitsCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_hits';
    public const COLLECTOR_DESCRIPTION = 'stats_hits gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['hits'];
        }
    }
}
