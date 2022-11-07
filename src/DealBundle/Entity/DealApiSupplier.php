<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealApiSupplier
 *
 * @ORM\Table(name="deal_api_supplier")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealApiSupplier
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var datetime
     *
     * @ORM\Column(name="creation_time", type="datetime", nullable=true)
     */
    private $creationTime;

    /**
     * @var string
     *
     * @ORM\Column(name="static_url", type="string", length=255, nullable=true)
     */
    private $staticUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="dynamic_url", type="string", length=255, nullable=true)
     */
    private $dynamicUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=255, nullable=true)
     */
    private $service;

    /**
     * Get id
     *
     * @return integer
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get email
     *
     * @return string
     */
    function getEmail()
    {
        return $this->email;
    }

    /**
     * Get name
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get creationTime
     *
     * @return timestamp
     */
    function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * Get staticUrl
     *
     * @return string
     */
    function getStaticUrl()
    {
        return $this->staticUrl;
    }

    /**
     * Get dynamicUrl
     *
     * @return string
     */
    function getDynamicUrl()
    {
        return $this->dynamicUrl;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return id
     */
    function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return email
     */
    function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return name
     */
    function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set creationTime
     *
     * @param timestamp $creationTime
     *
     * @return creationTime
     */
    function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * Set staticUrl
     *
     * @param string $staticUrl
     *
     * @return staticUrl
     */
    function setStaticUrl($staticUrl)
    {
        $this->staticUrl = $staticUrl;

        return $this;
    }

    /**
     * Set dynamicUrl
     *
     * @param string $dynamicUrl
     *
     * @return dynamicUrl
     */
    function setDynamicUrl($dynamicUrl)
    {
        $this->dynamicUrl = $dynamicUrl;

        return $this;
    }

    function getService()
    {
        return $this->service;
    }

    function setService($service)
    {
        $this->service = $service;
    }

    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}