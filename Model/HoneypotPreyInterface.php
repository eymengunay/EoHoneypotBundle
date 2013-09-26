<?php

namespace Eo\HoneypotBundle\Model;

/**
 * Eo\HoneypotBundle\Model\HoneypotPreyInterface
 */
interface HoneypotPreyInterface
{
    /**
     * Set ip
     *
     * @param string $ip
     * @return self
     */
    public function setIp($ip);

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp();
}
