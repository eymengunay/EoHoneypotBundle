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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            // Symfony 4.1+
            $treeBuilder = new TreeBuilder('eo_honeypot');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // Symfony <= 4.0
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('eo_honeypot');
        }

        $rootNode
            ->children()
                ->arrayNode('redirect')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('url')->defaultNull()->end()
                        ->scalarNode('route')->defaultNull()->end()
                        ->arrayNode('route_parameters')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('storage')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('database')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('class')->defaultValue('ApplicationEoHoneypotBundle:HoneypotPrey')->end()
                                ->scalarNode('driver')
                                    ->defaultValue('mongodb')
                                    ->validate()
                                    ->ifNotInArray(array('orm', 'mongodb'))
                                        ->thenInvalid('Invalid database driver "%s"')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('file')
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('output')->defaultValue('/var/log/honeypot.log')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
