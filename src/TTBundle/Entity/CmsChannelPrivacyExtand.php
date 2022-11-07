<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChannelPrivacyExtand
 *
 * @ORM\Table(name="cms_channel_privacy_extand")
 * @ORM\Entity
 */
class CmsChannelPrivacyExtand
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
     * @var integer
     *
     * @ORM\Column(name="channelid", type="integer", nullable=false)
     */
    private $channelid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_type", type="boolean", nullable=false)
     */
    private $privacyType;

    /**
     * @var string
     *
     * @ORM\Column(name="kind_type", type="text", length=65535, nullable=false)
     */
    private $kindType;

    /**
     * @var string
     *
     * @ORM\Column(name="connections", type="text", length=65535, nullable=false)
     */
    private $connections;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsors", type="text", length=65535, nullable=false)
     */
    private $sponsors;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set channelid
     *
     * @param integer $channelid
     *
     * @return CmsChannelPrivacyExtand
     */
    public function setChannelid($channelid)
    {
        $this->channelid = $channelid;

        return $this;
    }

    /**
     * Get channelid
     *
     * @return integer
     */
    public function getChannelid()
    {
        return $this->channelid;
    }

    /**
     * Set privacyType
     *
     * @param boolean $privacyType
     *
     * @return CmsChannelPrivacyExtand
     */
    public function setPrivacyType($privacyType)
    {
        $this->privacyType = $privacyType;

        return $this;
    }

    /**
     * Get privacyType
     *
     * @return boolean
     */
    public function getPrivacyType()
    {
        return $this->privacyType;
    }

    /**
     * Set kindType
     *
     * @param string $kindType
     *
     * @return CmsChannelPrivacyExtand
     */
    public function setKindType($kindType)
    {
        $this->kindType = $kindType;

        return $this;
    }

    /**
     * Get kindType
     *
     * @return string
     */
    public function getKindType()
    {
        return $this->kindType;
    }

    /**
     * Set connections
     *
     * @param string $connections
     *
     * @return CmsChannelPrivacyExtand
     */
    public function setConnections($connections)
    {
        $this->connections = $connections;

        return $this;
    }

    /**
     * Get connections
     *
     * @return string
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Set sponsors
     *
     * @param string $sponsors
     *
     * @return CmsChannelPrivacyExtand
     */
    public function setSponsors($sponsors)
    {
        $this->sponsors = $sponsors;

        return $this;
    }

    /**
     * Get sponsors
     *
     * @return string
     */
    public function getSponsors()
    {
        return $this->sponsors;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsChannelPrivacyExtand
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
}
