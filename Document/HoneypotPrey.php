<?php

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
    protected $request;

    /**
     * @ODM\String
     */
    protected $ip;

    /**
     * @ODM\Date
     */
    protected $createdAt;
}
