<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsManualRestartsCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'stats_manual_restarts';
    public const COLLECTOR_DESCRIPTION = 'stats_manual_restarts gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['opcache_statistics']['manual_restarts'];
        }
    }
}
