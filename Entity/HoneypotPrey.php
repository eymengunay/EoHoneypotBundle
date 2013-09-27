<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\Entity;

use Eo\HoneypotBundle\Model\HoneypotPrey as BaseHoneypotPrey;
use Doctrine\ORM\Mapping as ORM;

/**
 * Eo\HoneypotBundle\Entity\HoneypotPrey
 *
 * @ORM\Table(name="honeypot_prey")
 */
class HoneypotPrey extends BaseHoneypotPrey
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(length=45)
     */
    protected $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
}
