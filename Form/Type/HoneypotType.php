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

use Eo\HoneypotBundle\Events;
use Eo\HoneypotBundle\Event\BirdInCageEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormError;
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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Closure $this support was removed temporarily from PHP 5.3
        // and re-introduced with 5.4. This small hack is here for 5.3 compability.
        // https://wiki.php.net/rfc/closures/removal-of-this
        // http://php.net/manual/en/migration54.new-features.php
        $container = $this->container;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($container) {
            if ($event->getData()) {
                $request = $container->get('request');
                $hm = $container->get('eo_honeypot.manager');

                // Create new prey
                $prey = $hm->createNew($request->getClientIp());

                // Dispatch bird.in.cage event
                $container->get('event_dispatcher')->dispatch(Events::BIRD_IN_CAGE, new BirdInCageEvent($prey));

                // Save prey
                $hm->save($prey);

                $options = $hm->getOptions();
                if ($options['redirect']['enabled']) {
                    header(sprintf("Location: %s", $options['redirect']['to'] ? $options['redirect']['to'] : $request->getUri()));
                    exit;
                }
                $event->getForm()->getParent()->addError(new FormError('Form is invalid.'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'mapped'   => false,
            'data'     => '',
            'attr'     => array(
                'autocomplete' => 'off',
                'tabindex' => -1,
                // Fake `display:none` css behaviour to hide input
                // as some bots may also check inputs visibility
                'style' => 'position: absolute; left: -100%; top: -100%;'
            )
        ));
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
