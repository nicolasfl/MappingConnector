<?php

namespace Extensions\Bundle\MappingConnectorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class ExtensionsMappingConnectorExtension
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ExtensionsMappingConnectorExtension extends Extension
{

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('normalizers.yml');
        $loader->load('repositories.yml');
        $loader->load('entities.yml');
        $loader->load('jobs.yml');
        $loader->load('job_parameters.yml');
        $loader->load('array_converters.yml');
        $loader->load('providers.yml');
        $loader->load('readers.yml');
        $loader->load('steps.yml');
        $loader->load('writers.yml');
    }
}
