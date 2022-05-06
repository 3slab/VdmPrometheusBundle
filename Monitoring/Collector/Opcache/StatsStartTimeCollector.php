<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsStartTimeCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_start_time';
    public const COLLECTOR_DESCRIPTION = 'stats_start_time gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['start_time'];
        }
    }
}
