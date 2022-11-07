<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test1
 *
 * @ORM\Table(name="test1")
 * @ORM\Entity
 */
class Test1
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
     * @ORM\Column(name="factual_id", type="string", length=255, nullable=false)
     */
    private $factualId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;



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
     * Set factualId
     *
     * @param string $factualId
     *
     * @return Test1
     */
    public function setFactualId($factualId)
    {
        $this->factualId = $factualId;

        return $this;
    }

    /**
     * Get factualId
     *
     * @return string
     */
    public function getFactualId()
    {
        return $this->factualId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Test1
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
     * Set address
     *
     * @param string $address
     *
     * @return Test1
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
