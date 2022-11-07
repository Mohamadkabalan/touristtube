<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * AmadeusHotelSource
 *
 * @ORM\Table(name="amadeus_hotel_source")
 * @ORM\Entity
 */
class AmadeusHotelSource
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="HotelBundle\Entity\HotelReservation", mappedBy="source", cascade={"persist"})
     */
    private $reservations;

    /**
     * @ORM\ManyToOne(targetEntity="HotelBundle\Entity\AmadeusHotel", inversedBy="sources")
     * @ORM\JoinColumn(name="hotel_id", referencedColumnName="id")
     */
    private $hotel;

    /**
     * @var integer
     */
    private $hotelId = '0';

    /**
     * @var string
     */
    private $hotelCode = '';

    /**
     * @var string
     */
    private $chain = '';

    /**
     * @var string
     */
    private $chainName = '';

    /**
     * @var string
     */
    private $propertyIdentifier = '';

    /**
     * @var string
     */
    private $source = '';

    /**
     * @var string
     */
    private $providerValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="smallint", nullable=false)
     */
    private $published = '1';

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return Collection|Reservations[]
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Get hotel
     *
     * @return AmadeusHotel $hotel
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * Set hotel
     *
     * @param AmadeusHotel $hotel
     */
    public function setHotel(AmadeusHotel $hotel)
    {
        $this->hotel = $hotel;
    }

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
     * @return AmadeusHotelSource
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
     * Set hotelCode
     *
     * @param string $hotelCode
     *
     * @return AmadeusHotelSource
     */
    public function setHotelCode($hotelCode)
    {
        $this->hotelCode = $hotelCode;

        return $this;
    }

    /**
     * Get hotelCode
     *
     * @return string
     */
    public function getHotelCode()
    {
        return $this->hotelCode;
    }

    /**
     * Set chain
     *
     * @param string $chain
     *
     * @return AmadeusHotelSource
     */
    public function setChain($chain)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Get chain
     *
     * @return string
     */
    public function getChain()
    {
        return $this->chain;
    }

    /**
     * Set chainName
     *
     * @param string $chainName
     *
     * @return AmadeusHotelSource
     */
    public function setChainName($chainName)
    {
        $this->chainName = $chainName;

        return $this;
    }

    /**
     * Get chainName
     *
     * @return string
     */
    public function getChainName()
    {
        return $this->chainName;
    }

    /**
     * Set propertyIdentifier
     *
     * @param string $propertyIdentifier
     *
     * @return AmadeusHotelSource
     */
    public function setPropertyIdentifier($propertyIdentifier)
    {
        $this->propertyIdentifier = $propertyIdentifier;

        return $this;
    }

    /**
     * Get propertyIdentifier
     *
     * @return string
     */
    public function getPropertyIdentifier()
    {
        return $this->propertyIdentifier;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return AmadeusHotelSource
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set providerValue
     *
     * @param string $providerValue
     *
     * @return AmadeusHotel
     */
    public function setProviderValue($providerValue)
    {
        $this->providerValue = $providerValue;

        return $this;
    }

    /**
     * Get providerValue
     *
     * @return string
     */
    public function getProviderValue()
    {
        return $this->providerValue;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return AmadeusHotel
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }
}
