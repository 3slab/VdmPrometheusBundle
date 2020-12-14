<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring;

use Prometheus\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\AbstractCollector;

/**
 * Class CollectorRegistry
 *
 * The registry of data collectors
 *
 * @package Vdm\Bundle\PrometheusBundle\Monitoring
 */
class CollectorRegistry
{
    /**
     * @var AbstractCollector[]
     */
    protected $collectors = [];

    /**
     * Registry of collector to translate and save collected metrics into Prometheus format
     *
     * @var RegistryInterface
     */
    protected $registry;

    /**
     * The current name to label metrics
     *
     * @var string
     */
    protected $routeName;

    /**
     * The code of the App to label metrics
     * @var string
     */
    protected $appCode;

    /**
     * The namespace to prefix all metrics
     * @var string
     */
    protected $namespace;

    /**
     * CollectorRegistry constructor.
     *
     * @param string $appCode
     * @param string $namespace
     * @param RegistryInterface $registry
     */
    public function __construct(string $appCode, string $namespace, RegistryInterface $registry)
    {
        $this->appCode = $appCode;
        $this->namespace = $namespace;
        $this->registry = $registry;
    }

    /**
     * Add a collector to the registry
     *
     * @param AbstractCollector $collector
     */
    public function addCollector(AbstractCollector $collector)
    {
        $this->collectors[] = $collector;
    }

    /**
     * Trigger data collection on all collectors
     *
     * @param Request $request
     * @param Response $response
     */
    public function collect(Request $request, Response $response)
    {
        foreach ($this->collectors as $collector) {
            $collector->collect($request, $response);
        }
    }

    /**
     * Set the current route name
     *
     * @param string $routeName
     */
    public function setCurrentRoute($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * Save the collected metrics to the backend in Prometheus format
     *
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function save()
    {
        $standardLabels = ['app' => $this->appCode, 'route' => $this->routeName];

        foreach ($this->collectors as $collector) {
            $collector->save(
                $this->registry,
                $standardLabels
            );
        }
    }
}
