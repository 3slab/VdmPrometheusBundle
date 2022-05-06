<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsLastRestartTimeCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_last_restart_time';
    public const COLLECTOR_DESCRIPTION = 'stats_last_restart_time gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['last_restart_time'];
        }
    }
}
