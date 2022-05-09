<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NumSlotsCollector extends AbstractApcuCollector
{
    public const COLLECTOR_NAME = 'num_slots';
    public const COLLECTOR_DESCRIPTION = 'num_slots gauge';

    public function collect(Request $request, Response $response)
    {
        $cacheInfo = $this->getApcuCacheInfo();

        if (!empty($cacheInfo)) {
            $this->data = $cacheInfo['num_slots'];
        }
    }
}
