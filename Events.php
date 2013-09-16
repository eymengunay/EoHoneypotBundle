<?php

namespace Eo\HoneypotBundle;

final class Events
{
    /**
     * The bird.in.cage event is thrown if the hidden
     * form field has some data
     *
     * The event listener receives an
     * Eo\HoneypotBundle\Event\BirdInCageEvent instance.
     *
     * @var string
     */
    const BIRD_IN_CAGE = 'bird.in.cage';
}