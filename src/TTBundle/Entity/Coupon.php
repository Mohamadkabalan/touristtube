<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class Coupon
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
     * @ORM\Column(name="campaign_id", type="integer", nullable=false)
     */
    private $campaignId;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon_code", type="string", length=100, nullable=false)
     */
    private $couponCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="entity_id", type="string", length=100, nullable=false)
     */
    private $entityId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type_id", type="integer", nullable=false)
     */
    private $entityTypeId;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate = 'CURRENT_TIMESTAMP';

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
     * Set couponCode
     *
     * @param string $couponCode
     *
     * @return Coupon
     */
    public function setCouponCode($couponCode)
    {
        $this->couponCode = $couponCode;

        return $this;
    }

    /**
     * Get couponCode
     *
     * @return string
     */
    public function getCouponCode()
    {
        return $this->couponCode;
    }
    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Coupon
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    /**
     * Set campaignId
     *
     * @param integer $campaignId
     *
     * @return Coupon
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;

        return $this;
    }

    /**
     * Get campaignId
     *
     * @return integer
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }
    /**
     * Set entityId
     *
     * @param string $entityId
     *
     * @return Coupon
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
     * Set entityTypeId
     *
     * @param integer $entityTypeId
     *
     * @return Coupon
     */
    public function setEntityTypeId($entityTypeId)
    {
        $this->entityTypeId = $entityTypeId;

        return $this;
    }

    /**
     * Get entityTypeId
     *
     * @return integer
     */
    public function getEntityTypeId()
    {
        return $this->entityTypeId;
    }
}
