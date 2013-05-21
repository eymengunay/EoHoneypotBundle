<?php

namespace Eo\HoneypotBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Eo\HoneypotBundle\Document\HoneypotPrey
 *
 * @ODM\Document
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class HoneypotPrey
{
    /**
     * @var MongoId $id
     *
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @var hash $request
     *
     * @ODM\Field(name="request", type="hash")
     */
    protected $request;

    /**
     * @var hash $server
     *
     * @ODM\Field(name="server", type="hash")
     */
    protected $server;

    /**
     * @var string $ip
     *
     * @ODM\Field(name="ip", type="string")
     */
    protected $ip;

    /**
     * @var date $createdAt
     *
     * @ODM\Field(name="createdAt", type="date")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->ip = isset($_SERVER['REMOTE_ADDR']) ?: null;
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set request
     *
     * @param hash $request
     * @return self
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get request
     *
     * @return hash $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set server
     *
     * @param hash $server
     * @return self
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * Get server
     *
     * @return hash $server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
