<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MemoryCollector
 *
 * Handles peak memory usage
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring\Collector
 */
class MemoryCollector extends AbstractCollector
{
    public const COLLECTOR_NAME = 'sf_app_memory_usage';
    public const COLLECTOR_DESCRIPTION = 'Memory in byte per route';

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response)
    {
        $this->data = memory_get_peak_usage(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getCollectorName(): string
    {
        return static::COLLECTOR_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollectorDescription(): string
    {
        return static::COLLECTOR_DESCRIPTION;
    }
}
