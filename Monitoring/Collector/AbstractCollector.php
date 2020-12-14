<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector;

use Prometheus\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractCollector
 *
 * Base class for all metrics collector
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring\Collector
 */
abstract class AbstractCollector
{
    /**
     * Collected data
     *
     * @var mixed
     */
    protected $data;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * AbstractCollector constructor.
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Collect the metric using the request and/or response objects
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    abstract public function collect(Request $request, Response $response);

    /**
     * Get the name of the prometheus collector for this metric
     *
     * @return string
     */
    abstract public function getCollectorName(): string;

    /**
     * Get the help description of the prometheus collector for this metric
     *
     * @return string
     */
    abstract public function getCollectorDescription(): string;

    /**
     * Format the collected metric for prometheus and save it
     *
     * Note : the default implementation assumes that the metric is a gauge
     *
     * @param RegistryInterface $collector
     * @param array $standardLabels array of standard labels key and value
     *
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function save(RegistryInterface $collector, array $standardLabels)
    {
        $gauge = $collector->getOrRegisterGauge(
            $this->namespace,
            $this->getCollectorName(),
            $this->getCollectorDescription(),
            array_keys($standardLabels)
        );
        $gauge->set($this->getData(), array_values($standardLabels));
    }

    /**
     * Return the collected data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
