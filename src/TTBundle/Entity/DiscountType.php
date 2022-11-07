<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscountType
 *
 * @ORM\Table(name="discount_type")
 */
class DiscountType
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
     * @ORM\Column(name="discount_specs", type="string", nullable=true)
     */
    private $discountSpecs;


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
     * @return DiscountType
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
     * Set discountSpecs
     *
     * @param string $discountSpecs
     *
     * @return DiscountType
     */
    public function setDiscountSpecs($discountSpecs)
    {
        $this->discountSpecs = $discountSpecs;

        return $this;
    }

    /**
     * Get discountSpecs
     *
     * @return string
     */
    public function getDiscountSpecs()
    {
        return $this->discountSpecs;
    }
}
