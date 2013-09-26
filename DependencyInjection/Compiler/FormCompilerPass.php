<?php

namespace Eo\HoneypotBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Processes twig configuration
 */
class FormCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');
        if (in_array('EoHoneypotBundle:Form:div_layout.html.twig', $resources) === false) {
            array_unshift($resources, 'EoHoneypotBundle:Form:div_layout.html.twig');
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
