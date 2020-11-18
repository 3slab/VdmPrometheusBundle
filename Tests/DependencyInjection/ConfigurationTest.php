<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Vdm\Bundle\PrometheusBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    /**
     * @var Processor
     */
    protected $processor;

    public function setUp(): void
    {
        $this->processor = new Processor();
    }

    public function testEmptyConfig(): void
    {
        $configuration = new Configuration();
        $config = $this->processor->processConfiguration($configuration, []);

        $this->assertEquals(
            [
                'secret' => null,
                'metrics_path' => '/metrics',
                'storage' => ['type' => 'memory', 'service' => null],
            ],
            $config
        );
    }

    public function testApcuConfig(): void
    {
        $configuration = new Configuration();
        $config = $this->processor->processConfiguration(
            $configuration,
            [
                'vdm_prometheus' => [
                    'storage' => [
                        'type' => 'apcu'
                    ]
                ]
            ]
        );

        $this->assertEquals(
            [
                'secret' => null,
                'metrics_path' => '/metrics',
                'storage' => ['type' => 'apcu', 'service' => null]
            ],
            $config
        );
    }

    public function testDefaultRedisConfig(): void
    {
        $configuration = new Configuration();
        $config = $this->processor->processConfiguration(
            $configuration,
            [
                'vdm_prometheus' => [
                    'storage' => [
                        'type' => 'redis'
                    ]
                ]
            ]
        );

        $this->assertEquals(
            [
                'secret' => null,
                'metrics_path' => '/metrics',
                'storage' => [
                    'type' => 'redis',
                    'settings' => [
                        'host' => '127.0.0.1',
                        'port' => 6379,
                        'timeout' => 0.1,
                        'read_timeout' => '10',
                        'persistent_connections' => false,
                        'password' => null
                    ],
                    'service' => null
                ]
            ],
            $config
        );
    }

    public function testInvalidConfigMetricPath(): void
    {
        $configuration = new Configuration();
        $config = $this->processor->processConfiguration(
            $configuration,
            [
                'vdm_prometheus' => [
                    'metrics_path' => true,
                ]
            ]
        );

        $this->assertEquals(
            [
                'secret' => null,
                'metrics_path' => '/metrics',
                'storage' => ['type' => 'memory', 'service' => null]
            ],
            $config
        );
    }

    public function testInvalidConfigStorageType(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $configuration = new Configuration();
        $this->processor->processConfiguration(
            $configuration,
            [
                'vdm_prometheus' => [
                    'storage' => [
                        'type' => 'unknown'
                    ]
                ]
            ]
        );
    }

    public function testInvalidConfigWithException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $configuration = new Configuration();
        $this->processor->processConfiguration(
            $configuration,
            [
                'vdm_prometheus' => [
                    'storage' => [
                        'type' => 'redis',
                        'settings' => [
                            'persistent_connections' => 'invalid'
                        ]
                    ]
                ]
            ]
        );
    }

    public function testInvalidConfigCustomTypeWithoutService(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $configuration = new Configuration();
        $this->processor->processConfiguration(
            $configuration,
            [
                'vdm_prometheus' => [
                    'storage' => [
                        'type' => 'custom'
                    ]
                ]
            ]
        );
    }

    /**
     * @testWith ["memory"]
     *           ["apcu"]
     *
     * @param string $storageType
     */
    public function testValidConfigNotRedisAndCustom(string $storageType): void
    {
        $unprocessedConfig = [
            'vdm_prometheus' => [
                'secret' => 'mysecret',
                'metrics_path' => '/mycustommetrics',
                'storage' => [
                    'type' => $storageType,
                    'service' => null
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->processor->processConfiguration(
            $configuration,
            $unprocessedConfig
        );

        $this->assertEquals($unprocessedConfig['vdm_prometheus'], $config);
    }

    public function testValidConfigRedis(): void
    {
        $unprocessedConfig = [
            'vdm_prometheus' => [
                'secret' => 'mysecret',
                'metrics_path' => '/mycustommetrics',
                'storage' => [
                    'type' => 'redis',
                    'settings' => [
                        'host' => 'myhost',
                        'port' => 6666,
                        'timeout' => 0.11,
                        'read_timeout' => '15',
                        'persistent_connections' => true,
                        'password' => 'passphrase'
                    ],
                    'service' => null
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->processor->processConfiguration(
            $configuration,
            $unprocessedConfig
        );

        $this->assertEquals($unprocessedConfig['vdm_prometheus'], $config);
    }

    public function testValidConfigCustom(): void
    {
        $unprocessedConfig = [
            'vdm_prometheus' => [
                'secret' => 'mysecret',
                'metrics_path' => '/mycustommetrics',
                'storage' => [
                    'type' => 'custom',
                    'service' => 'myserviceid'
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->processor->processConfiguration(
            $configuration,
            $unprocessedConfig
        );

        $this->assertEquals($unprocessedConfig['vdm_prometheus'], $config);
    }
}
