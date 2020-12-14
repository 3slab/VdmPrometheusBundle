<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector;

use Prometheus\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TotalNumberOfCallCollector
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring\Collector
 */
class TotalNumberOfCallCollector extends AbstractCollector
{
    public const COLLECTOR_NAME = 'sf_app_call_total';
    public const COLLECTOR_DESCRIPTION = 'Number of call to the app';

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response)
    {
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

    /**
     * {@inheritdoc}
     *
     * Note : overridden because sf_app_call_total is a counter
     */
    public function save(RegistryInterface $collector, array $standardLabels)
    {
        $counter = $collector->getOrRegisterCounter(
            $this->namespace,
            $this->getCollectorName(),
            $this->getCollectorDescription(),
            array_keys($standardLabels)
        );
        $counter->inc(array_values($standardLabels));
    }
}
