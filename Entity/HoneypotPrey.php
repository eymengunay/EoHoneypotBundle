<?php

namespace Eo\HoneypotBundle\Entity;

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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $request;

    /**
     * @ORM\Column(length=45)
     */
    protected $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
}
