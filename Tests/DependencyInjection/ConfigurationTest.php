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
                'to' => false
            ),
            'storage' => array(
                'database' => array(
                    'enabled' => false,
                    'class' => 'EoHoneypotBundle:HoneypotPrey',
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