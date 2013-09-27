<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\Event;

use Eo\HoneypotBundle\Model\HoneypotPreyInterface;
use Symfony\Component\EventDispatcher\Event;

class BirdInCageEvent extends Event
{
    /**
     * @var HoneypotPreyInterface
     */
    protected $prey;

    /**
     * Class constructor
     *
     * @param HoneypotPreyInterface $prey
     */
    public function __construct(HoneypotPreyInterface $prey)
    {
        $this->prey = $prey;
    }

    /**
     * @return HoneypotPreyInterface
     */
    public function getPrey()
    {
        return $this->prey;
    }
}
