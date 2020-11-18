<?php

namespace Vdm\Bundle\PrometheusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CustomStoragePass
 *
 * Container compiler pass to manage the custom storage
 *
 * @package Vdm\Bundle\PrometheusBundle\DependencyInjection\Compiler
 */
class CustomStoragePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('vdm_prometheus.storage.service_id')) {
            return;
        }

        $serviceId = $container->getParameter('vdm_prometheus.storage.service_id');
        if (!$container->has($serviceId)) {
            throw new ServiceNotFoundException(sprintf('service %s not found', $serviceId));
        }

        $container->removeDefinition('vdm_prometheus_storage');
        $prometheusDefinition = $container->getDefinition('vdm_prometheus_registry');
        $prometheusDefinition->replaceArgument(0, new Reference($serviceId));
    }
}
