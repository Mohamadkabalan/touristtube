<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealSupplierTypeStatus
 *
 * @ORM\Table(name="deal_supplier_type_status")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 */
class DealSupplierTypeStatus
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
     * @ORM\Column(name="deal_api_supplier_id", type="bigint", nullable=false)
     */
    private $dealApiSupplierId;

    /**
     * @var integer
     *
     * @ORM\Column(name="deal_type_id", type="bigint", nullable=false)
     */
    private $dealTypeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_active", type="tinyint", nullable=false)
     */
    private $isActive;

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
     * Get dealApiSupplierId
     *
     * @return integer
     */
    function getDealApiSupplierId()
    {
        return $this->dealApiSupplierId;
    }

    /**
     * Get dealTypeId
     *
     * @return integer
     */
    function getDealTypeId()
    {
        return $this->dealTypeId;
    }

    /**
     * Get isActive
     *
     * @return integer
     */
    function getIsActive()
    {
        return $this->isActive;
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
     * Set dealApiSupplierId
     *
     * @param integer $dealApiSupplierId
     *
     * @return dealApiSupplierId
     */
    function setDealApiSupplierId($dealApiSupplierId)
    {
        $this->dealApiSupplierId = $dealApiSupplierId;

        return $this;
    }

    /**
     * Set dealTypeId
     *
     * @param integer $dealTypeId
     *
     * @return dealTypeId
     */
    function setDealTypeId($dealTypeId)
    {
        $this->dealTypeId = $dealTypeId;

        return $this;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     *
     * @return isActive
     */
    function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }
}