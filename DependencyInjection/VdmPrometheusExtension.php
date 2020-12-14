<?php

namespace Vdm\Bundle\PrometheusBundle\DependencyInjection;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * VdmPrometheusExtension
 */
class VdmPrometheusExtension extends ConfigurableExtension
{
    protected const MAPPING_TYPE_STORAGE = [
        'apcu' => APC::class,
        'redis' => Redis::class,
        'memory' => InMemory::class,
        'custom' => null
    ];

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $container->setParameter('vdm_prometheus.app_code', $mergedConfig['app']);
        $container->setParameter('vdm_prometheus.namespace', $mergedConfig['namespace']);
        $container->setParameter('vdm_prometheus.secret', $mergedConfig['secret']);
        $container->setParameter('vdm_prometheus.metrics_path', $mergedConfig['metrics_path']);

        if (!empty($mergedConfig['storage']['service'])) {
            $container->setParameter('vdm_prometheus.storage.service_id', $mergedConfig['storage']['service']);
        }

        // Prometheus registry
        $prometheusDefinition = $container->register('vdm_prometheus_registry', CollectorRegistry::class);
        $prometheusDefinition->setPublic(true);

        // Prometheus storage adapter
        $storageClass = self::MAPPING_TYPE_STORAGE[$mergedConfig['storage']['type']];
        $storageDefinition = $container->register('vdm_prometheus_storage', $storageClass);
        if (!empty($mergedConfig['storage']['settings'])) {
            $storageDefinition->setArguments([$mergedConfig['storage']['settings']]);
        }

        $prometheusDefinition->setArguments([
            new Reference('vdm_prometheus_storage'),
            $mergedConfig['register_default_metrics']
        ]);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }
}
