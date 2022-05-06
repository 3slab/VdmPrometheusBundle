<?php

namespace Vdm\Bundle\PrometheusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('vdm_prometheus');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('app')
                    ->defaultValue('app')
                ->end()
                ->scalarNode('namespace')
                    ->defaultValue('vdm')
                ->end()
                ->booleanNode('register_default_metrics')
                    ->defaultTrue()
                ->end()
                ->arrayNode('register_extra_metrics')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('secret')
                    ->defaultNull()
                ->end()
                ->scalarNode('metrics_path')
                    ->treatFalseLike('/metrics')
                    ->treatTrueLike('/metrics')
                    ->treatNullLike('/metrics')
                    ->defaultValue('/metrics')
                ->end()
                ->arrayNode('storage')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->always()
                        ->then(function ($v) {
                            if (!is_array($v)) {
                                throw new \InvalidArgumentException(
                                    sprintf('Invalid storage configuration %s', json_encode($v))
                                );
                            }

                            // needs a default type for next if condition
                            if (empty($v['type'])) {
                                $v['type'] = 'memory';
                            }

                            // set settings default in beforeNormalization because conditional to type value
                            if ($v['type'] === 'redis' && empty($v['settings'])) {
                                return [
                                    'type' => 'redis',
                                    'settings' => [
                                        'host' => '127.0.0.1',
                                        'port' => 6379,
                                        'timeout' => 0.1,
                                        'read_timeout' => '10',
                                        'persistent_connections' => false,
                                        'password' => null
                                    ]
                                ];
                            }

                            // remove default settings if not redis storage type
                            if ($v['type'] !== 'redis') {
                                unset($v['settings']);
                            }

                            return $v;
                        })
                    ->end()
                    ->validate()
                        ->always()
                        ->then(function ($v) {
                            if ($v['type'] === 'custom' && empty($v['service'])) {
                                throw new \InvalidArgumentException('Storage custom needs a service');
                            }

                            return $v;
                        })
                    ->end()
                    ->children()
                        ->enumNode('type')
                            ->defaultValue('memory')
                            ->values(['memory', 'apcu', 'redis', 'custom'])
                        ->end()
                        ->arrayNode('settings')
                            ->children()
                                ->scalarNode('host')->end()
                                ->integerNode('port')->end()
                                ->floatNode('timeout')->end()
                                ->scalarNode('read_timeout')->end()
                                ->booleanNode('persistent_connections')->end()
                                ->scalarNode('password')->end()
                            ->end()
                        ->end()
                        ->scalarNode('service')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
