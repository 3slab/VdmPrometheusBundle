<?php

/**
 * @package    3slab/VdmPrometheusBundle
 * @copyright  2020 Suez Smart Solutions 3S.lab
 * @license    https://github.com/3slab/VdmPrometheusBundle/blob/master/LICENSE
 */

namespace Vdm\Bundle\PrometheusBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vdm\Bundle\PrometheusBundle\DependencyInjection\Compiler\CollectorPass;
use Vdm\Bundle\PrometheusBundle\DependencyInjection\Compiler\CustomStoragePass;
use Vdm\Bundle\PrometheusBundle\Monitoring\Collector\AbstractCollector;

class VdmPrometheusBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->registerForAutoconfiguration(AbstractCollector::class)
            ->addTag('vdm_prometheus.collector')
        ;

        $container->addCompilerPass(new CustomStoragePass());
        $container->addCompilerPass(new CollectorPass());
    }
}
