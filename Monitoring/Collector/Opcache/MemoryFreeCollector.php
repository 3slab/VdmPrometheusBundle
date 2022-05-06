<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemoryFreeCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'memory_free';
    public const COLLECTOR_DESCRIPTION = 'memory_free gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['memory_usage']['free_memory'];
        }
    }
}
