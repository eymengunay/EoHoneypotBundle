<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
