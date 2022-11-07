<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Discount
 *
 * @ORM\Table(name="discount")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class Discount
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
     * @var integer
     *
     * @ORM\Column(name="discount_type_id", type="integer", nullable=false)
     */
    private $discountTypeId = '1';
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="discount_value", type="decimal", nullable=false)
     */
    private $discountValue = '1.000000';
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="threshold_value", type="decimal", nullable=false)
     */
    private $thresholdValue = '0.000000';


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
     * @return Discount
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
     * @return discount
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
     * Set discountTypeId
     *
     * @param integer $discountTypeId
     *
     * @return discount
     */
    public function setDiscountTypeId($discountTypeId)
    {
        $this->discountTypeId = $discountTypeId;

        return $this;
    }

    /**
     * Get discountTypeId
     *
     * @return integer
     */
    public function getDiscountTypeIdy()
    {
        return $this->discountTypeId;
    }
    /**
     * Set discountValue
     *
     * @param integer $discountValue
     *
     * @return discount
     */
    public function setDiscountValue($discountValue)
    {
        $this->discountValue = $discountValue;

        return $this;
    }

    /**
     * Get discountValue
     *
     * @return integer
     */
    public function getDiscountValue()
    {
        return $this->discountValue;
    }
    /**
     * Set thresholdValue
     *
     * @param integer $thresholdValue
     *
     * @return discount
     */
    public function setThresholdValue($thresholdValue)
    {
        $this->thresholdValue = $thresholdValue;

        return $this;
    }

    /**
     * Get thresholdValue
     *
     * @return integer
     */
    public function getThresholdValue()
    {
        return $this->thresholdValue;
    }

}
