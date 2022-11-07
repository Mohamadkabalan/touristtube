<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatastellarCountrySubSubSubToHotel
 *
 * @ORM\Table(name="datastellar_country_sub_sub_sub_to_hotel", uniqueConstraints={@ORM\UniqueConstraint(name="hotel_id", columns={"hotel_id", "country_sub_sub_sub_id"})}, indexes={@ORM\Index(name="country_sub_sub_sub_id", columns={"country_sub_sub_sub_id"})})
 * @ORM\Entity
 */
class DatastellarCountrySubSubSubToHotel
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
     * @ORM\Column(name="country_sub_sub_sub_id", type="integer", nullable=false)
     */
    private $countrySubSubSubId;



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
     * @return DatastellarCountrySubSubSubToHotel
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
     * Set countrySubSubSubId
     *
     * @param integer $countrySubSubSubId
     *
     * @return DatastellarCountrySubSubSubToHotel
     */
    public function setCountrySubSubSubId($countrySubSubSubId)
    {
        $this->countrySubSubSubId = $countrySubSubSubId;

        return $this;
    }

    /**
     * Get countrySubSubSubId
     *
     * @return integer
     */
    public function getCountrySubSubSubId()
    {
        return $this->countrySubSubSubId;
    }
}
