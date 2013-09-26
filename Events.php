<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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