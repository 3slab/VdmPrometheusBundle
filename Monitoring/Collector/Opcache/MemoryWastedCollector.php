<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemoryWastedCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'memory_wasted';
    public const COLLECTOR_DESCRIPTION = 'memory_wasted gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['memory_usage']['wasted_memory'];
        }
    }
}
