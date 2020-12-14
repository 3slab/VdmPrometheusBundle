<?php

namespace Vdm\Bundle\PrometheusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Vdm\Bundle\PrometheusBundle\Monitoring\CollectorRegistry;

/**
 * Class CollectorPass
 *
 * Container compiler pass to load collectors into the registry service
 *
 * @package Vdm\Bundle\PrometheusBundle\DependencyInjection\Compiler
 */
class CollectorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CollectorRegistry::class)) {
            return;
        }

        $definition = $container->findDefinition(CollectorRegistry::class);

        $taggedServices = $container
            ->findTaggedServiceIds('vdm_prometheus.collector')
        ;

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addCollector', array(new Reference($id)));
        }
    }
}
