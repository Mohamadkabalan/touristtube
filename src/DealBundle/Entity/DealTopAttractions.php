<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealTopAttractions
 *
 * @ORM\Table(name="deal_top_attractions", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealTopAttractions
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_api_supplier_id", type="bigint", nullable=true)
     */
    private $dealApiSupplierId;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=true)
     */
    private $cityId;

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getImageUrl()
    {
        return $this->imageUrl;
    }

    function getDealApiSupplierId()
    {
        return $this->dealApiSupplierId;
    }

    function getCityId()
    {
        return $this->cityId;
    }
    
    function setName($name)
    {
        $this->name = $name;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    function setDealApiSupplierId($dealApiSupplierId)
    {
        $this->dealApiSupplierId = $dealApiSupplierId;
    }
    
    function setCityId($cityId)
    {
        $this->cityId = $cityId;
    }
}