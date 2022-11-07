<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiscoverHotelsRooms
 *
 * @ORM\Table(name="discover_hotels_rooms", indexes={@ORM\Index(name="hotel_id", columns={"hotel_id"})})
 * @ORM\Entity
 */
class DiscoverHotelsRooms
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_person", type="integer", nullable=false)
     */
    private $numPerson;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=255, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="pic1", type="string", length=255, nullable=false)
     */
    private $pic1;

    /**
     * @var string
     *
     * @ORM\Column(name="pic2", type="string", length=255, nullable=false)
     */
    private $pic2;

    /**
     * @var string
     *
     * @ORM\Column(name="pic3", type="string", length=255, nullable=false)
     */
    private $pic3;



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
     * @return DiscoverHotelsRooms
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
     * Set title
     *
     * @param string $title
     *
     * @return DiscoverHotelsRooms
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
     * Set description
     *
     * @param string $description
     *
     * @return DiscoverHotelsRooms
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set numPerson
     *
     * @param integer $numPerson
     *
     * @return DiscoverHotelsRooms
     */
    public function setNumPerson($numPerson)
    {
        $this->numPerson = $numPerson;

        return $this;
    }

    /**
     * Get numPerson
     *
     * @return integer
     */
    public function getNumPerson()
    {
        return $this->numPerson;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return DiscoverHotelsRooms
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set pic1
     *
     * @param string $pic1
     *
     * @return DiscoverHotelsRooms
     */
    public function setPic1($pic1)
    {
        $this->pic1 = $pic1;

        return $this;
    }

    /**
     * Get pic1
     *
     * @return string
     */
    public function getPic1()
    {
        return $this->pic1;
    }

    /**
     * Set pic2
     *
     * @param string $pic2
     *
     * @return DiscoverHotelsRooms
     */
    public function setPic2($pic2)
    {
        $this->pic2 = $pic2;

        return $this;
    }

    /**
     * Get pic2
     *
     * @return string
     */
    public function getPic2()
    {
        return $this->pic2;
    }

    /**
     * Set pic3
     *
     * @param string $pic3
     *
     * @return DiscoverHotelsRooms
     */
    public function setPic3($pic3)
    {
        $this->pic3 = $pic3;

        return $this;
    }

    /**
     * Get pic3
     *
     * @return string
     */
    public function getPic3()
    {
        return $this->pic3;
    }
}
