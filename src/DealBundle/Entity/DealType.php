<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealType
 *
 * @ORM\Table(name="deal_type")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealType
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
     * @ORM\Column(name="category", type="string", length=32, nullable=true)
     */
    private $category;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="creation_time", type="datetime", nullable=true)
     */
    private $creationTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", length=11, nullable=false)
     */
    private $displayOrder;

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
     * Get name
     *
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get category
     *
     * @return string
     */
    function getCategory()
    {
        return $this->category;
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
     * Get displayOrder
     *
     * @return integer
     */
    function getDisplayOrder()
    {
        return $this->displayOrder;
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
     * Set category
     *
     * @param string $category
     *
     * @return category
     */
    function setCategory($category)
    {
        $this->category = $category;

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
     * Set displayOrder
     *
     * @param integer $displayOrder
     *
     * @return displayOrder
     */
    function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
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