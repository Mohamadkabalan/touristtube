<?php

namespace DealBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransferTypeVendor
 *
 * @ORM\Table(name="transfer_type_vendor")
 */
class TransferTypeVendor
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
     * @ORM\Column(name="vendor_id", type="tinyInt", nullable=false)
     */
    private $vendorId;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_transfer_type_code", type="string", length=50, nullable=false)
     */
    private $vendorTransferTypeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_transfer_type_name", type="string", length=255, nullable=false)
     */
    private $vendorTransferTypeName;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_transfer_type_description", type="string", length=255, nullable=false)
     */
    private $vendorTransferTypeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_transfer_type_image", type="string", length=255, nullable=false)
     */
    private $vendorTransferTypeImage;

    function getId()
    {
        return $this->id;
    }

    function getVendorId()
    {
        return $this->vendorId;
    }

    function getVendorTransferTypeCode()
    {
        return $this->vendorTransferTypeCode;
    }

    function getVendorTransferTypeName()
    {
        return $this->vendorTransferTypeName;
    }

    function getVendorTransferTypeDescription()
    {
        return $this->vendorTransferTypeDescription;
    }

    function getVendorTransferTypeImage()
    {
        return $this->vendorTransferTypeImage;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
    }

    function setVendorTransferTypeCode($vendorTransferTypeCode)
    {
        $this->vendorTransferTypeCode = $vendorTransferTypeCode;
    }

    function setVendorTransferTypeName($vendorTransferTypeName)
    {
        $this->vendorTransferTypeName = $vendorTransferTypeName;
    }

    function setVendorTransferTypeDescription($vendorTransferTypeDescription)
    {
        $this->vendorTransferTypeDescription = $vendorTransferTypeDescription;
    }

    function setVendorTransferTypeImage($vendorTransferTypeImage)
    {
        $this->vendorTransferTypeImage = $vendorTransferTypeImage;
    }
}