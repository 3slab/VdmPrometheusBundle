<?php

namespace Vdm\Bundle\PrometheusBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Vdm\Bundle\PrometheusBundle\VdmPrometheusBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @var array
     */
    protected $additionnalBundles = [];

    /**
     * @var string
     */
    protected $customProjectDir;

    public function __construct(string $customProjectDir)
    {
        //$_SERVER['SHELL_VERBOSITY'] = -1;
        parent::__construct('test', true);
        $this->customProjectDir = $customProjectDir;
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXTS, 'glob');

        $loader->load($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');
    }

    public function addBundles(array $bundles = [])
    {
        $this->additionnalBundles = $bundles;
    }

    public function registerBundles(): iterable
    {
        return array_merge([
            new FrameworkBundle(),
            new VdmPrometheusBundle(),
        ], $this->additionnalBundles);
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__) . '/' . $this->customProjectDir;
    }
}
