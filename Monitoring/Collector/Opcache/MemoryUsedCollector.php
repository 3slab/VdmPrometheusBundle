<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemoryUsedCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'memory_used';
    public const COLLECTOR_DESCRIPTION = 'memory_used gauge';

    public function collect(Request $request, Response $response)
    {
        $stats = opcache_get_status(false);

        $this->data = $stats['memory_usage']['used_memory'];
    }
}
