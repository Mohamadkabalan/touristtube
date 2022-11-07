<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverFacilities
 *
 * @ORM\Table(name="discover_facilities")
 * @ORM\Entity
 */
class DiscoverFacilities
{
    /**
     * @var float
     *
     * @ORM\Column(name="id", type="float")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=510, nullable=true)
     */
    private $title;



    /**
     * Get id
     *
     * @return float
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return DiscoverFacilities
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
