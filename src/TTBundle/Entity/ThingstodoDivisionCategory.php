<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ThingstodoDivisionCategory
 *
 * @ORM\Table(name="thingstodo_division_category")
 * @ORM\Entity(repositoryClass="TTBundle\Repository\DiscoverQueryRQRepository")
 */
class ThingstodoDivisionCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ThingstodoDivisionCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
