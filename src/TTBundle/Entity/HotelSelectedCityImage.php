<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HotelSelectedCityImage
 *
 * @ORM\Table(name="hotel_selected_city_image", indexes={@ORM\Index(name="hotel_selected_city_id", columns={"hotel_selected_city_id"}), @ORM\Index(name="published", columns={"published"})})
 */
class HotelSelectedCityImage
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
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg_price", type="integer", nullable=false)
     */
    private $avgPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_selected_city_id", type="integer", nullable=false)
     */
    private $hotelSelectedCityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_order", type="integer", nullable=false)
     */
    private $displayOrder='0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

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
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return HotelSelectedCityImage
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    /**
     * Get hotelId
     *
     * @return integer
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set avgPrice
     *
     * @param integer $avgPrice
     *
     * @return HotelSelectedCityImage
     */
    public function setAvgPrice($avgPrice)
    {
        $this->avgPrice = $avgPrice;

        return $this;
    }

    /**
     * Get avgPrice
     *
     * @return integer
     */
    public function getAvgPrice()
    {
        return $this->avgPrice;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return HotelSelectedCityImage
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set hotelSelectedCityId
     *
     * @param integer $hotelSelectedCityId
     *
     * @return HotelSelectedCityImage
     */
    public function setHotelSelectedCityId($hotelSelectedCityId)
    {
        $this->hotelSelectedCityId = $hotelSelectedCityId;

        return $this;
    }

    /**
     * Get hotelSelectedCityId
     *
     * @return integer
     */
    public function getHotelSelectedCityId()
    {
        return $this->hotelSelectedCityId;
    }

    /**
     * Set displayOrder
     *
     * @param integer $displayOrder
     *
     * @return HotelSelectedCityImage
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return HotelSelectedCityImage
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
}
