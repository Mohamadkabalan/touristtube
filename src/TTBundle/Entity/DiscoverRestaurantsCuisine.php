<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverRestaurantsCuisine
 *
 * @ORM\Table(name="discover_restaurants_cuisine")
 * @ORM\Entity
 */
class DiscoverRestaurantsCuisine
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cuisine_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $cuisineId;

    /**
     * @var integer
     *
     * @ORM\Column(name="restaurant_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $restaurantId;



    /**
     * Set cuisineId
     *
     * @param integer $cuisineId
     *
     * @return DiscoverRestaurantsCuisine
     */
    public function setCuisineId($cuisineId)
    {
        $this->cuisineId = $cuisineId;

        return $this;
    }

    /**
     * Get cuisineId
     *
     * @return integer
     */
    public function getCuisineId()
    {
        return $this->cuisineId;
    }

    /**
     * Set restaurantId
     *
     * @param integer $restaurantId
     *
     * @return DiscoverRestaurantsCuisine
     */
    public function setRestaurantId($restaurantId)
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }

    /**
     * Get restaurantId
     *
     * @return integer
     */
    public function getRestaurantId()
    {
        return $this->restaurantId;
    }
}
