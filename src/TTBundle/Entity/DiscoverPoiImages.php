<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverPoiImages
 *
 * @ORM\Table(name="discover_poi_images", indexes={@ORM\Index(name="poi_id", columns={"poi_id"})})
 * @ORM\Entity
 */
class DiscoverPoiImages
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
    private $userId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="default_pic", type="integer", nullable=false)
     */
    private $defaultPic = '0';

    /**
     * @var \TTBundle\Entity\DiscoverPoi
     *
     * @ORM\ManyToOne(targetEntity="TTBundle\Entity\DiscoverPoi", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="poi_id", referencedColumnName="id")
     * })
     */
    private $poi;



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
     * @return DiscoverPoiImages
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
     * @return DiscoverPoiImages
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
     * Set defaultPic
     *
     * @param integer $defaultPic
     *
     * @return DiscoverPoiImages
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

    /**
     * Set poi
     *
     * @param \TTBundle\Entity\DiscoverPoi $poi
     *
     * @return DiscoverPoiImages
     */
    public function setPoi(\TTBundle\Entity\DiscoverPoi $poi = null)
    {
        $this->poi = $poi;

        return $this;
    }

    /**
     * Get poi
     *
     * @return \TTBundle\Entity\DiscoverPoi
     */
    public function getPoi()
    {
        return $this->poi;
    }
}
