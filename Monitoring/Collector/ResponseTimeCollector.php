<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ResponseTimeCollector
 *
 * Handles response time
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring\Collector
 */
class ResponseTimeCollector extends AbstractCollector
{
    public const COLLECTOR_NAME = 'sf_app_response_time';
    public const COLLECTOR_DESCRIPTION = 'Request execution time in seconds';

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * ResponseTimeDataCollector constructor.
     *
     * @param string $namespace
     * @param KernelInterface|null $kernel
     */
    public function __construct(string $namespace, KernelInterface $kernel = null)
    {
        parent::__construct($namespace);
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response)
    {
        $startTime = null;

        if (null !== $this->kernel) {
            $startTime = $this->kernel->getStartTime();
        }

        if (is_null($startTime) || $startTime === -INF) {
            $startTime = $request->server->get('REQUEST_TIME_FLOAT');
        }

        $this->data = (microtime(true) - $startTime) * 1000;
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
