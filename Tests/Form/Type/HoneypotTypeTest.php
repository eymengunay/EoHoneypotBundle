<?php

namespace Eo\HoneypotBundle\Tests\Form\Type;

use Eo\HoneypotBundle\Events;
use Eo\HoneypotBundle\Form\Type\HoneypotType;
use Eo\HoneypotBundle\Model\HoneypotPrey;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;

class HoneypotTypeTest extends \PHPUnit_Framework_TestCase
{
    private $triggered;

    protected function setUp()
    {
        $this->triggered = false;
    }

    public function buildFormDataProvider()
    {
        return array(
            array(null,         false),
            array('',           false),
            array('Stupid bot', true),
        );
    }

    /**
     * @dataProvider buildFormDataProvider
     */
    public function testBuildForm($data, $trigger)
    {
        $honeypotManager = $this->getMockBuilder('Eo\HoneypotBundle\Manager\HoneypotManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $honeypotManager
            ->expects($this->any())
            ->method('createNew')
            ->will($this->returnValue(new HoneypotPrey()))
        ;

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener(Events::BIRD_IN_CAGE, function () {
            $this->triggered = true;
        });

        $factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $builder = new FormBuilder('honeypot', null, $eventDispatcher, $factory);

        $type = new HoneypotType(new Request(), $honeypotManager, $eventDispatcher);
        $type->buildForm($builder, array());

        $parent = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $form = $builder->getForm();
        $form->setParent($parent);

        $eventDispatcher->dispatch(FormEvents::PRE_SUBMIT, new FormEvent($form, $data));

        $this->assertEquals($this->triggered, $trigger);
    }
}
