<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DealBookingStatusSupplier
 *
 * @ORM\Table(name="deal_booking_status_supplier")
 * @ORM\Entity(repositoryClass="DealBundle\Repository\Deal\PackagesRepository")
 * @ORM\Entity
 */
class DealBookingStatusSupplier
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tt_status", type="string", length=50, nullable=false)
     */
    private $ttStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="supplier_id", type="integer", nullable=false)
     */
    private $supplierId;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier_status", type="string", length=50, nullable=false)
     */
    private $supplierStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    function getId()
    {
        return $this->id;
    }

    function getTtStatus()
    {
        return $this->ttStatus;
    }

    function getSupplierId()
    {
        return $this->supplierId;
    }

    function getSupplierStatus()
    {
        return $this->supplierStatus;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setTtStatus($ttStatus)
    {
        $this->ttStatus = $ttStatus;
    }

    function setSupplierId($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    function setSupplierStatus($supplierStatus)
    {
        $this->supplierStatus = $supplierStatus;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}