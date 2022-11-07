<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotelsFeature
 *
 * @ORM\Table(name="discover_hotels_feature", uniqueConstraints={@ORM\UniqueConstraint(name="title", columns={"title", "feature_type"})}, indexes={@ORM\Index(name="type", columns={"feature_type"})})
 * @ORM\Entity
 */
class DiscoverHotelsFeature
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
     * @ORM\Column(name="title", type="string", length=127, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="feature_type", type="integer", nullable=false)
     */
    private $featureType;



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
     * Set title
     *
     * @param string $title
     *
     * @return DiscoverHotelsFeature
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

    /**
     * Set featureType
     *
     * @param integer $featureType
     *
     * @return DiscoverHotelsFeature
     */
    public function setFeatureType($featureType)
    {
        $this->featureType = $featureType;

        return $this;
    }

    /**
     * Get featureType
     *
     * @return integer
     */
    public function getFeatureType()
    {
        return $this->featureType;
    }
}
