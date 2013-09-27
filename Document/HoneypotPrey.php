<?php

/*
 * This file is part of the EoHoneypotBundle package.
 *
 * (c) Eymen Gunay <eymen@egunay.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eo\HoneypotBundle\Document;

use Eo\HoneypotBundle\Model\HoneypotPrey as BaseHoneypotPrey;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Eo\HoneypotBundle\Document\HoneypotPrey
 *
 * @ODM\Document
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class HoneypotPrey extends BaseHoneypotPrey
{
    /**
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $ip;

    /**
     * @ODM\Date
     */
    protected $createdAt;
}
