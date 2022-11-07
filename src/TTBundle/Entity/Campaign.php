<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 *
 * @ORM\Table(name="campaign")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class Campaign
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
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate = 'CURRENT_TIMESTAMP';
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate = 'CURRENT_TIMESTAMP';
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="currency_id", type="integer", nullable=false)
     */
    private $currencyId = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="discount_id", type="integer", nullable=false)
     */
    private $discountId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="source_entity_type_id", type="integer", nullable=false)
     */
    private $sourceEntityTypeId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="target_entity_type_id", type="integer", nullable=false)
     */
    private $targetEntityTypeId;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive = true;
	
	/**
     * @var string
     *
     * @ORM\Column(name="marketing_label", type="string", nullable=true)
     */
    private $marketingLabel;

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
     * @return Campaign
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Campaign
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Campaign
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Campaign
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    /**
     * Set currencyId
     *
     * @param integer $currencyId
     *
     * @return Campaign
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    /**
     * Get currencyId
     *
     * @return integer
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }
    /**
     * Set discountId
     *
     * @param integer $discountId
     *
     * @return Campaign
     */
    public function setDiscountId($discountId)
    {
        $this->discountId = $discountId;

        return $this;
    }

    /**
     * Get discountId
     *
     * @return integer
     */
    public function getDiscountId()
    {
        return $this->discountId;
    }
    /**
     * Set sourceEntityTypeId
     *
     * @param integer $sourceEntityTypeId
     *
     * @return Campaign
     */
    public function setSourceEntityTypeId($sourceEntityTypeId)
    {
        $this->sourceEntityTypeId = $sourceEntityTypeId;

        return $this;
    }

    /**
     * Get sourceEntityTypeId
     *
     * @return integer
     */
    public function getSourceEntityTypeId()
    {
        return $this->sourceEntityTypeId;
    }
    /**
     * Set targetEntityTypeId
     *
     * @param integer $targetEntityTypeId
     *
     * @return Campaign
     */
    public function setTargetEntityTypeId($targetEntityTypeId)
    {
        $this->targetEntityTypeId = $targetEntityTypeId;

        return $this;
    }

    /**
     * Get targetEntityTypeId
     *
     * @return integer
     */
    public function getTargetEntityTypeId()
    {
        return $this->targetEntityTypeId;
    }
    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Campaign
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
	
	/**
     * Set marketingLabel
     *
     * @param string $label
     *
     * @return Campaign
     */
    public function setMarketingLabel($label)
    {
        $this->marketingLabel = $label;

        return $this;
    }

    /**
     * Get marketingLabel
     *
     * @return string
     */
    public function getMarketingLabel()
    {
        return $this->marketingLabel;
    }
}
