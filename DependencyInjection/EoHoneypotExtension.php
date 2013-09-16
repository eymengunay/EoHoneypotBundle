<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EoHoneypotExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('eo_honeypot.use_db', $config['use_db']);
        $container->setParameter('eo_honeypot.db_driver', $config['db_driver']);

        // Define honeypot form type
        $definition = new Definition('Eo\HoneypotBundle\Form\Type\HoneypotType', array($config['use_db']));
        $definition->addTag('form.type', array('alias' => 'honeypot'));
        $definition->addMethodCall('setContainer', array(new Reference('doctrine.odm.mongodb.document_manager')));
        $container->setDefinition('eo_honeypot.form.type.honeypot', $definition);
    }
}
