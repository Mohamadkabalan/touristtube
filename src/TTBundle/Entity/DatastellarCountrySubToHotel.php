<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatastellarCountrySubToHotel
 *
 * @ORM\Table(name="datastellar_country_sub_to_hotel", uniqueConstraints={@ORM\UniqueConstraint(name="hotel_id", columns={"hotel_id", "country_sub_id"})}, indexes={@ORM\Index(name="country_sub_id", columns={"country_sub_id"})})
 * @ORM\Entity
 */
class DatastellarCountrySubToHotel
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
     * @var integer
     *
     * @ORM\Column(name="country_sub_id", type="integer", nullable=false)
     */
    private $countrySubId;



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
     * @return DatastellarCountrySubToHotel
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
     * Set countrySubId
     *
     * @param integer $countrySubId
     *
     * @return DatastellarCountrySubToHotel
     */
    public function setCountrySubId($countrySubId)
    {
        $this->countrySubId = $countrySubId;

        return $this;
    }

    /**
     * Get countrySubId
     *
     * @return integer
     */
    public function getCountrySubId()
    {
        return $this->countrySubId;
    }
}
