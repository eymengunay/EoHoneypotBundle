<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;

class HoneypotManager
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Class constructor
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Is blacklisted
     * 
     * @return boolean
     */
    public function isBlacklisted()
    {
        if ($this->container->getParameter('eo_honeypot.use_db') == false) {
            throw new \LogicException("Can not check ip without a database");
        }
        return count($this->getRepository()->findBy(array('ip' => $this->container->get('request')->getClientIp()))) > 0;
    }

    /**
     * Get object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->container->get($this->container->getParameter('eo_honeypot.db_service'))->getManager();
    }

    /**
     * Get object manager
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->container->getParameter('eo_honeypot.db_class'));
    }
}
