<?php

namespace Vdm\Bundle\PrometheusBundle\Monitoring\Collector\Apcu;

use Prometheus\RegistryInterface;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\AbstractCollector;

abstract class AbstractApcuCollector extends AbstractCollector
{
    public function __construct()
    {
        parent::__construct('apcu');
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

    protected function getApcuCacheInfo(): array
    {
        if (function_exists('apcu_enabled') && function_exists('apcu_cache_info')) {
            if (apcu_enabled()) {
                $cacheInfo = apcu_cache_info(true);

                if ($cacheInfo !== false) {
                    return $cacheInfo;
                }
            }
        }

        return [];
    }
}
