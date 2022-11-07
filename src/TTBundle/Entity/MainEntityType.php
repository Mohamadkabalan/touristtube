<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MainEntityType
 *
 * @ORM\Table(name="main_entity_type")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class MainEntityType
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
     * @var integer
     *
     * @ORM\Column(name="entity_type_id", type="integer", nullable=true)
     */
    private $entityTypeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=true)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    private $displayOrder;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_on_home", type="integer", nullable=false)
     */
    private $showOnHome = '0';

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
     * Set name
     *
     * @param string $name
     *
     * @return MainEntityType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set entityTypeId
     *
     * @param string $entityTypeId
     *
     * @return MainEntityType
     */
    public function setEntityTypeId($entityTypeId)
    {
        $this->entityTypeId = $entityTypeId;

        return $this;
    }

    /**
     * Get entityTypeId
     *
     * @return string
     */
    public function getEntityTypeId()
    {
        return $this->entityTypeId;
    }

    /**
     * Set entityId
     *
     * @param string $entityId
     *
     * @return MainEntityType
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set displayOrder
     *
     * @param string $displayOrder
     *
     * @return MainEntityType
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return string
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set showOnHome
     *
     * @param boolean $showOnHome
     *
     * @return MainEntityType
     */
    public function setShowOnHome($showOnHome)
    {
        $this->showOnHome = $showOnHome;

        return $this;
    }

    /**
     * Get showOnHome
     *
     * @return boolean
     */
    public function getShowOnHome()
    {
        return $this->showOnHome;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return MainEntityType
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
