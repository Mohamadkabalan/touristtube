<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealImage
 *
 * @ORM\Table(name="deal_image", indexes={@ORM\Index(name="deal_id", columns={"deal_id"})})
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealImage
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
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="image_description", type="string", length=255, nullable=true)
     */
    private $imageDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_detail_id", type="integer", nullable=true)
     */
    private $dealDetailId;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_default", type="integer", nullable=true)
     */
    private $isDefault;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_id", type="integer", nullable=false)
     */
    private $dealId = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="image_order", type="integer", nullable=false)
     */
    private $imageOrder = '1';

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
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get imageDescription
     *
     * @return string
     */
    public function getImageDescription()
    {
        return $this->imageDescription;
    }

    /**
     * Get dealDetailId
     *
     * @return integer
     */
    public function getDealDetailId()
    {
        return $this->dealDetailId;
    }

    /**
     * Get isDefault
     *
     * @return integer
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Get dealId
     *
     * @return integer
     */
    public function getDealId()
    {
        return $this->dealId;
    }

    /**
     * Get imageOrder
     *
     * @return integer
     */
    public function getImageOrder()
    {
        return $this->imageOrder;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return DealImage
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set imageDescription
     *
     * @param string $imageDescription
     *
     * @return imageDescription
     */
    public function setImageDescription($imageDescription)
    {
        $this->imageDescription = $imageDescription;

        return $this;
    }

    /**
     * Set dealDetailId
     *
     * @param integer $dealDetailId
     *
     * @return dealDetailId
     */
    public function setDealDetailId($dealDetailId)
    {
        $this->dealDetailId = $dealDetailId;

        return $this;
    }

    /**
     * Set isDefault
     *
     * @param integer $isDefault
     *
     * @return isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Set dealId
     *
     * @param integer $dealId
     *
     * @return DealImage
     */
    public function setDealId($dealId)
    {
        $this->dealId = $dealId;

        return $this;
    }

    /**
     * Set imageOrder
     *
     * @param integer $imageOrder
     *
     * @return DealImage
     */
    public function setImageOrder($imageOrder)
    {
        $this->imageOrder = $imageOrder;

        return $this;
    }
}