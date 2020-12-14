<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector;

use Prometheus\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseCodeCollector
 *
 * Handles response status code
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring\Collector
 */
class ResponseCodeCollector extends AbstractCollector
{
    public const COLLECTOR_NAME = 'sf_app_response_code_total';
    public const COLLECTOR_DESCRIPTION = 'Number of call to the API per response code';

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response)
    {
        $this->data = $response->getStatusCode();
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
     * Note : overridden because sf_app_response_code_total is a counter
     */
    public function save(RegistryInterface $collector, array $standardLabels)
    {
        $counter = $collector->getOrRegisterCounter(
            $this->namespace,
            $this->getCollectorName(),
            $this->getCollectorDescription(),
            array_merge(array_keys($standardLabels), ['http_code'])
        );
        $counter->inc(array_merge(array_values($standardLabels), [(string) $this->data]));
    }
}
