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
use Eo\HoneypotBundle\Manager\HoneypotManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoneypotType extends AbstractType
{
    /**
     * @var Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @var Eo\HoneypotBundle\Manager\HoneypotManager
     */
    protected $honeypotManager;

    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Class constructor
     *
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param Eo\HoneypotBundle\Manager\HoneypotManager $honeypotManager
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(RequestStack $requestStack, HoneypotManager $honeypotManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->requestStack = $requestStack;
        $this->honeypotManager = $honeypotManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Closure $this support was removed temporarily from PHP 5.3
        // and re-introduced with 5.4. This small hack is here for 5.3 compability.
        // https://wiki.php.net/rfc/closures/removal-of-this
        // http://php.net/manual/en/migration54.new-features.php
        $request = $this->requestStack->getCurrentRequest();
        $honeypotManager = $this->honeypotManager;
        $eventDispatcher = $this->eventDispatcher;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($request, $honeypotManager, $eventDispatcher, $options) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!$data) {
                return;
            }

            // Create new prey
            $prey = $honeypotManager->createNew($request->getClientIp());

            // Dispatch bird.in.cage event
            $eventDispatcher->dispatch(new BirdInCageEvent($prey), Events::BIRD_IN_CAGE);

            // Save prey
            $honeypotManager->save($prey);

            if ($options['causesError']) {
                $form->getParent()->addError(new FormError('Form is invalid.'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'required'    => false,
            'mapped'      => false,
            'data'        => '',
            'causesError' => true,
            'attr'        => array(
                // autocomplete="off" does not work in some cases, random strings always do
                'autocomplete' => 'nope',
                // Make the field unfocusable for keyboard users
                'tabindex' => -1,
                // Hide the field from assistive technology like screen readers
                'aria-hidden' => 'true',
                // Fake `display:none` css behaviour to hide input
                // as some bots may also check inputs visibility
                'style' => 'position: fixed; left: -100%; top: -100%;'
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }
}
