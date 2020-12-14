<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseSizeCollector
 *
 * Handles response size
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring\Collector
 */
class ResponseSizeCollector extends AbstractCollector
{
    public const COLLECTOR_NAME = 'sf_app_response_size';
    public const COLLECTOR_DESCRIPTION = 'Response size in bytes';

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response)
    {
        $this->data = strlen($response->getContent());
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
