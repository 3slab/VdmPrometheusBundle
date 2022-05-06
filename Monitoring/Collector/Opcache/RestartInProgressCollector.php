<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestartInProgressCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'restart_in_progress';
    public const COLLECTOR_DESCRIPTION = 'restart_in_progress gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['restart_in_progress'] ? 1 : 0;
        }
    }
}
