<?php

namespace Eo\HoneypotBundle\Tests\Event;

use Eo\HoneypotBundle\Document\HoneypotPrey;
use Eo\HoneypotBundle\Event\BirdInCageEvent;

class BirdInCageEventTest extends \PHPUnit_Framework_TestCase
{
    public function testPrey()
    {
        $prey  = new HoneypotPrey();
        $event = new BirdInCageEvent($prey);

        $this->assertEquals($event->getPrey(), $prey);
    }
}