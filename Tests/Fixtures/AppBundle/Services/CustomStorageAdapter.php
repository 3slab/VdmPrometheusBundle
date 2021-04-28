<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Fixtures\AppBundle\Services;

use Prometheus\Exception\StorageException;
use Prometheus\MetricFamilySamples;
use Prometheus\Storage\Adapter;

class CustomStorageAdapter implements Adapter
{
    public function collect(): array
    {
        return ['custom' => 'correctly instanciated'];
    }

    public function updateHistogram(array $data): void
    {
        // TODO: Implement updateHistogram() method.
    }

    public function updateGauge(array $data): void
    {
        // TODO: Implement updateGauge() method.
    }

    public function updateCounter(array $data): void
    {
        // TODO: Implement updateCounter() method.
    }

    public function wipeStorage(): void
    {
        // TODO: Implement wipeStorage() method.
    }
}
