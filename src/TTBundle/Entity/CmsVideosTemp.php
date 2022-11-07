<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsVideosTemp
 *
 * @ORM\Table(name="cms_videos_temp", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="catalog_id", columns={"catalog_id"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\PhotosVideosRepository")
 */
class CmsVideosTemp
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upload_ts", type="datetime", nullable=false)
     */
    private $uploadTs = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="vpath", type="string", length=128, nullable=false)
     */
    private $vpath;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="string", length=255, nullable=false)
     */
    private $thumb;

    /**
     * @var integer
     *
     * @ORM\Column(name="catalog_id", type="integer", nullable=true)
     */
    private $catalogId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="image_video", type="string", length=1, nullable=false)
     */
    private $imageVideo;

    /**
     * @var integer
     *
     * @ORM\Column(name="channelid", type="integer", nullable=true)
     */
    private $channelid;

    /**
     * @var string
     *
     * @ORM\Column(name="pending_data", type="text", length=65535, nullable=false)
     */
    private $pendingData;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_value", type="text", length=65535, nullable=false)
     */
    private $privacyValue;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_array", type="text", length=65535, nullable=false)
     */
    private $privacyArray;



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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsVideosTemp
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return CmsVideosTemp
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set uploadTs
     *
     * @param \DateTime $uploadTs
     *
     * @return CmsVideosTemp
     */
    public function setUploadTs($uploadTs)
    {
        $this->uploadTs = $uploadTs;

        return $this;
    }

    /**
     * Get uploadTs
     *
     * @return \DateTime
     */
    public function getUploadTs()
    {
        return $this->uploadTs;
    }

    /**
     * Set vpath
     *
     * @param string $vpath
     *
     * @return CmsVideosTemp
     */
    public function setVpath($vpath)
    {
        $this->vpath = $vpath;

        return $this;
    }

    /**
     * Get vpath
     *
     * @return string
     */
    public function getVpath()
    {
        return $this->vpath;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     *
     * @return CmsVideosTemp
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Get thumb
     *
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Set catalogId
     *
     * @param integer $catalogId
     *
     * @return CmsVideosTemp
     */
    public function setCatalogId($catalogId)
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * Get catalogId
     *
     * @return integer
     */
    public function getCatalogId()
    {
        return $this->catalogId;
    }

    /**
     * Set imageVideo
     *
     * @param string $imageVideo
     *
     * @return CmsVideosTemp
     */
    public function setImageVideo($imageVideo)
    {
        $this->imageVideo = $imageVideo;

        return $this;
    }

    /**
     * Get imageVideo
     *
     * @return string
     */
    public function getImageVideo()
    {
        return $this->imageVideo;
    }

    /**
     * Set channelid
     *
     * @param integer $channelid
     *
     * @return CmsVideosTemp
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
     * Set pendingData
     *
     * @param string $pendingData
     *
     * @return CmsVideosTemp
     */
    public function setPendingData($pendingData)
    {
        $this->pendingData = $pendingData;

        return $this;
    }

    /**
     * Get pendingData
     *
     * @return string
     */
    public function getPendingData()
    {
        return $this->pendingData;
    }

    /**
     * Set privacyValue
     *
     * @param string $privacyValue
     *
     * @return CmsVideosTemp
     */
    public function setPrivacyValue($privacyValue)
    {
        $this->privacyValue = $privacyValue;

        return $this;
    }

    /**
     * Get privacyValue
     *
     * @return string
     */
    public function getPrivacyValue()
    {
        return $this->privacyValue;
    }

    /**
     * Set privacyArray
     *
     * @param string $privacyArray
     *
     * @return CmsVideosTemp
     */
    public function setPrivacyArray($privacyArray)
    {
        $this->privacyArray = $privacyArray;

        return $this;
    }

    /**
     * Get privacyArray
     *
     * @return string
     */
    public function getPrivacyArray()
    {
        return $this->privacyArray;
    }
}
