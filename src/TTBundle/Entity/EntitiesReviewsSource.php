<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntitiesReviewsSource
 *
 * @ORM\Table(name="entities_reviews_source")
 * @ORM\Entity
 */
class EntitiesReviewsSource
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="module_id", type="integer", nullable=false)
     */
    private $moduleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="vendor_id", type="integer", nullable=false)
     */
    private $vendorId;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_review_id", type="string", length=50, nullable=false)
     */
    private $vendorReviewId;

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
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return EntitiesReviewsSource
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get moduleId
     *
     * @return integer
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Set moduleId
     *
     * @param integer $moduleId
     *
     * @return EntitiesReviewsSource
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;

        return $this;
    }

    /**
     * Get vendorId
     *
     * @return integer
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }

    /**
     * Set vendorId
     *
     * @param integer $vendorId
     *
     * @return EntitiesReviewsSource
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;

        return $this;
    }

    /**
     * Get vendorReviewId
     *
     * @return string
     */
    public function getVendorReviewId()
    {
        return $this->vendorReviewId;
    }

    /**
     * Set vendorReviewId
     *
     * @param string $vendorReviewId
     *
     * @return EntitiesReviewsSource
     */
    public function setVendorReviewId($vendorReviewId)
    {
        $this->vendorReviewId = $vendorReviewId;

        return $this;
    }
}