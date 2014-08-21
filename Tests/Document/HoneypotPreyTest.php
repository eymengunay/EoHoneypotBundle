<?php

namespace Eo\HoneypotBundle\Tests\Document;

class HoneypotPreyTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $now  = new \DateTime();
        $prey = $stub = $this->getMockForAbstractClass('Eo\HoneypotBundle\Document\HoneypotPrey', array('127.0.0.1'));
        $prey->setCreatedAt($now);

        $this->assertEquals($prey->getIp(), '127.0.0.1');
        $this->assertEquals($prey->getCreatedAt(), $now);

        $prey->setIp('10.0.0.1');
        $this->assertEquals($prey->getIp(), '10.0.0.1');
    }
}
