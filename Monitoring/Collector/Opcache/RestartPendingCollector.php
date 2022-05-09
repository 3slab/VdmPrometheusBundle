<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestartPendingCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'restart_pending';
    public const COLLECTOR_DESCRIPTION = 'restart_pending gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['restart_pending'] ? 1 : 0;
        }
    }
}
