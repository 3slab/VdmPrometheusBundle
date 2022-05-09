<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InternedStringsCountCollector extends AbstractOpcacheCollector
{
    public const COLLECTOR_NAME = 'interned_strings_count';
    public const COLLECTOR_DESCRIPTION = 'interned_strings_count gauge';

    public function collect(Request $request, Response $response)
    {
        $status = $this->getOpcacheStatus();

        if (!empty($status)) {
            $this->data = $status['interned_strings_usage']['number_of_strings'];
        }
    }
}
