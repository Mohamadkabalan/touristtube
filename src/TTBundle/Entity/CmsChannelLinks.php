<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChannelLinks
 *
 * @ORM\Table(name="cms_channel_links")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\ChannelRepository")
 */
class CmsChannelLinks
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
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=254, nullable=false)
     */
    private $link;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_social", type="integer", nullable=false)
     */
    private $isSocial = '1';



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
     * @return CmsChannelLinks
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
     * Set link
     *
     * @param string $link
     *
     * @return CmsChannelLinks
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsChannelLinks
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

    /**
     * Set isSocial
     *
     * @param integer $isSocial
     *
     * @return CmsChannelLinks
     */
    public function setIsSocial($isSocial)
    {
        $this->isSocial = $isSocial;

        return $this;
    }

    /**
     * Get isSocial
     *
     * @return integer
     */
    public function getIsSocial()
    {
        return $this->isSocial;
    }
}
