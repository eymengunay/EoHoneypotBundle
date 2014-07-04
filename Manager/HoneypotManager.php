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

use Eo\HoneypotBundle\Model\HoneypotPrey;
use Eo\HoneypotBundle\Model\HoneypotPreyInterface;
use Doctrine\Common\Persistence\ObjectManager;

class HoneypotManager
{
    /**
     * @var array
     */
    protected $options;
    
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * Class constructor
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Is blacklisted
     *
     * @param  string  $ip
     * @return boolean
     */
    public function isBlacklisted($ip)
    {
        if (!$this->isDbEnabled()) {
            throw new \LogicException("Can not check ip without database storage");
        }
        return count($this->getRepository()->findBy(array('ip' => $ip))) > 0;
    }

    /**
     * Create new
     *
     * @param  string $ip
     * @return HoneypotPreyInterface
     */
    public function createNew($ip)
    {
        if (!$this->isDbEnabled()) {
            $prey = new HoneypotPrey($ip);
        } else {
            $class = $this->getRepository()->getClassName();
            $prey = new $class($ip);
        }
        return $prey;
    }

    /**
     * Save prey
     *
     * @param HoneypotPreyInterface $prey
     */
    public function save(HoneypotPreyInterface $prey)
    {
        if ($this->isDbEnabled()) {
            $this->getObjectManager()->persist($prey);
            $this->getObjectManager()->flush($prey);
        }

        if ($this->isFileEnabled()) {
            $data = sprintf("[%s] - %s\n", $prey->getCreatedAt()->format('c'), $prey->getIp());
            file_put_contents($this->options['storage']['file']['output'], $data, FILE_APPEND);
        }
    }

    /**
     * Set object manager
     *
     * @param  ObjectManager $om
     * @return self
     */
    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
        return $this;
    }

    /**
     * Get object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->om;
    }

    /**
     * Get object manager
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->options['storage']['database']['class']);
    }

    /**
     * Is db enabled
     *
     * @return boolean
     */
    public function isDbEnabled()
    {
        return $this->options['storage']['database']['enabled'];
    }

    /**
     * Is file enabled
     *
     * @return boolean
     */
    public function isFileEnabled()
    {
        return $this->options['storage']['file']['enabled'];
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
