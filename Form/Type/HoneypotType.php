<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Eo\HoneypotBundle\Document\HoneypotPrey;
use Eo\HoneypotBundle\Event\BirdInCageEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HoneypotType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_BIND, function(FormEvent $event) {
            if ($event->getData() !== "") {

                // Dispatch bird in cage event
                $event = new BirdInCageEvent($seance);
                $this->container->get('event_dispatcher')->dispatch(Events::MAP_GENERATE, $event);

                $request = $this->container->get('request');

                // Check if we need to save request in db
                if ($this->container->getParameter('eo_honeypot.use_db')) {
                    $prey = new HoneypotPrey();
                    $prey
                        ->setRequest(strval($request))
                        ->setIp($request->getClientIp())
                    ;

                    $om = $this->getManager();
                    $om->persist($prey);
                    $om->flush($prey);
                }

                header(sprintf("Location: %s", $request->get));
                exit;
            }
        });
    }

    /**
     * Get manager
     * 
     * @return ObjectManager
     */
    public function getManager()
    {
        $id = false;
        switch ($this->container->getParameter('eo_honeypot.db_driver')) {
            case 'orm':
                $id = 'doctrine';
                break;
            case 'mongodb':
                $id = 'doctrine_mongodb';
                break;
            default:
                throw new \LogicException("Invalid db driver given");
                break;
        }
        return $this->container->get($id)->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'virtual'  => true
        ));
    }

    /**
     * Set container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'honeypot';
    }
}