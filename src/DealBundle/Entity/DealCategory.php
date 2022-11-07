<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealCategory
 *
 * @ORM\Table(name="deal_category")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealCategory
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="api_category_id", type="integer", nullable=true)
     */
    private $apiCategoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_api_id", type="integer", nullable=true)
     */
    private $dealApiId;

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getParentId()
    {
        return $this->parentId;
    }

    function getApiCategoryId()
    {
        return $this->apiCategoryId;
    }

    function getDealApiId()
    {
        return $this->dealApiId;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    function setApiCategoryId($apiCategoryId)
    {
        $this->apiCategoryId = $apiCategoryId;
    }

    function setDealApiId($dealApiId)
    {
        $this->dealApiId = $dealApiId;
    }
}