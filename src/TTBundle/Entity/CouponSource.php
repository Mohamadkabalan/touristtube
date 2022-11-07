<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CouponSource
 *
 * @ORM\Table(name="coupon_source")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\TTRepository")
 */
class CouponSource
{
    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $entityTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="source_specs", type="string", nullable=true)
     */
    private $sourceSpecs;
	
	/**
     * @var string
     *
     * @ORM\Column(name="helper_text", type="string", nullable=true)
     */
    private $helperText;

    /**
     * Get entityTypeId
     *
     * @return integer
     */
    public function getEntityTypeId()
    {
        return $this->entityTypeId;
    }

    /**
     * Set sourceSpecs
     *
     * @param string $sourceSpecs
     *
     * @return CouponSource
     */
    public function setSourceSpecs($sourceSpecs)
    {
        $this->sourceSpecs = $sourceSpecs;

        return $this;
    }

    /**
     * Get sourceSpecs
     *
     * @return string
     */
    public function getSourceSpecs()
    {
        return $this->sourceSpecs;
    }
	
	/**
     * Set helperText
     *
     * @param string $label
     *
     * @return CouponSource
     */
    public function setHelperText($label)
    {
        $this->helperText = $label;

        return $this;
    }

    /**
     * Get helperText
     *
     * @return string
     */
    public function getHelperText()
    {
        return $this->helperText;
    }
}
