<?php

namespace Eo\HoneypotBundle\Tests\DependencyInjection;

use Eo\HoneypotBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array());

        $this->assertEquals(array(
            'redirect' => array(
                'enabled' => false,
                'url' => null,
                'route' => null,
                'route_parameters' => array(),
            ),
            'storage' => array(
                'database' => array(
                    'enabled' => false,
                    'class' => 'ApplicationEoHoneypotBundle:HoneypotPrey',
                    'driver' => 'mongodb'
                ),
                'file' => array(
                    'enabled' => false,
                    'output' => '/var/log/honeypot.log'
                )
            )
        ), $config);
    }
}
