<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsVideosCatalogs
 *
 * @ORM\Table(name="cms_videos_catalogs", indexes={@ORM\Index(name="video_id", columns={"video_id", "catalog_id"}), @ORM\Index(name="catalog_id", columns={"catalog_id"})})
 * @ORM\Entity(repositoryClass="TTBundle\Repository\PhotosVideosRepository")
 */
class CmsVideosCatalogs
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
     * @ORM\Column(name="video_id", type="integer", nullable=false)
     */
    private $videoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="catalog_id", type="integer", nullable=false)
     */
    private $catalogId;

    /**
     * @var integer
     *
     * @ORM\Column(name="default_pic", type="integer", nullable=false)
     */
    private $defaultPic = '0';



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
     * Set videoId
     *
     * @param integer $videoId
     *
     * @return CmsVideosCatalogs
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Get videoId
     *
     * @return integer
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * Set catalogId
     *
     * @param integer $catalogId
     *
     * @return CmsVideosCatalogs
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
     * Set defaultPic
     *
     * @param integer $defaultPic
     *
     * @return CmsVideosCatalogs
     */
    public function setDefaultPic($defaultPic)
    {
        $this->defaultPic = $defaultPic;

        return $this;
    }

    /**
     * Get defaultPic
     *
     * @return integer
     */
    public function getDefaultPic()
    {
        return $this->defaultPic;
    }
}
