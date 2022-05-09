<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InternedStringsUsedCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'interned_strings_used';
    public const COLLECTOR_DESCRIPTION = 'interned_strings_used gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['interned_strings_usage']['used_memory'];
        }
    }
}
