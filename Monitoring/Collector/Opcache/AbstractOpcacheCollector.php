<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Opcache;

use Prometheus\RegistryInterface;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\AbstractCollector;

abstract class AbstractOpcacheCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('opcache');
    }

    public function getCollectorName(): string
    {
        return static::COLLECTOR_NAME;
    }

    public function getCollectorDescription(): string
    {
        return static::COLLECTOR_DESCRIPTION;
    }

    public function save(RegistryInterface $collector, array $standardLabels)
    {
        $gauge = $collector->getOrRegisterGauge(
            $this->namespace,
            $this->getCollectorName(),
            ''
        );
        $gauge->set($this->getData());
    }

    protected function getOpcacheStatus(): array
    {
        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status(false);

            if ($status !== false && $status['opcache_enabled']) {
                return $status;
            }
        }

        return [];
    }
}
