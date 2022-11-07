<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GlobalRestaurants
 *
 * @ORM\Table(name="global_restaurants", indexes={@ORM\Index(name="locality", columns={"locality"}), @ORM\Index(name="country", columns={"country"}), @ORM\Index(name="region", columns={"region"}), @ORM\Index(name="admin_region", columns={"admin_region"}), @ORM\Index(name="latitude", columns={"latitude"}), @ORM\Index(name="longitude", columns={"longitude"})})
 * @ORM\Entity
 */
class GlobalRestaurants
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
     * @var string
     *
     * @ORM\Column(name="address_extended", type="string", length=255, nullable=false)
     */
    private $addressExtended;

    /**
     * @var string
     *
     * @ORM\Column(name="po_box", type="string", length=255, nullable=false)
     */
    private $poBox;

    /**
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=255, nullable=false)
     */
    private $locality;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="post_town", type="string", length=255, nullable=false)
     */
    private $postTown;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_region", type="string", length=255, nullable=false)
     */
    private $adminRegion;

    /**
     * @var integer
     *
     * @ORM\Column(name="postcode", type="integer", nullable=false)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=false)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="neighborhood", type="string", length=255, nullable=false)
     */
    private $neighborhood;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=false)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="category_ids", type="string", length=255, nullable=false)
     */
    private $categoryIds;

    /**
     * @var string
     *
     * @ORM\Column(name="category_labels", type="string", length=255, nullable=false)
     */
    private $categoryLabels;

    /**
     * @var string
     *
     * @ORM\Column(name="chain_name", type="string", length=255, nullable=false)
     */
    private $chainName;

    /**
     * @var string
     *
     * @ORM\Column(name="chain_id", type="string", length=255, nullable=false)
     */
    private $chainId;

    /**
     * @var string
     *
     * @ORM\Column(name="hours", type="string", length=255, nullable=false)
     */
    private $hours;

    /**
     * @var string
     *
     * @ORM\Column(name="hours_display", type="string", length=255, nullable=false)
     */
    private $hoursDisplay;

    /**
     * @var string
     *
     * @ORM\Column(name="existence", type="string", length=255, nullable=false)
     */
    private $existence;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="zoom_order", type="boolean", nullable=false)
     */
    private $zoomOrder = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="from_source", type="string", length=255, nullable=false)
     */
    private $fromSource = 'factual';

    /**
     * @var float
     *
     * @ORM\Column(name="avg_rating", type="float", precision=10, scale=0, nullable=false)
     */
    private $avgRating = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_votes", type="integer", nullable=false)
     */
    private $nbVotes = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="avg_price", type="integer", nullable=false)
     */
    private $avgPrice = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="map_image", type="string", length=255, nullable=false)
     */
    private $mapImage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="updated", type="boolean", nullable=false)
     */
    private $updated = '1';



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
     * @return GlobalRestaurants
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
     * @return GlobalRestaurants
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
     * @return GlobalRestaurants
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

    /**
     * Set addressExtended
     *
     * @param string $addressExtended
     *
     * @return GlobalRestaurants
     */
    public function setAddressExtended($addressExtended)
    {
        $this->addressExtended = $addressExtended;

        return $this;
    }

    /**
     * Get addressExtended
     *
     * @return string
     */
    public function getAddressExtended()
    {
        return $this->addressExtended;
    }

    /**
     * Set poBox
     *
     * @param string $poBox
     *
     * @return GlobalRestaurants
     */
    public function setPoBox($poBox)
    {
        $this->poBox = $poBox;

        return $this;
    }

    /**
     * Get poBox
     *
     * @return string
     */
    public function getPoBox()
    {
        return $this->poBox;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return GlobalRestaurants
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return GlobalRestaurants
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set postTown
     *
     * @param string $postTown
     *
     * @return GlobalRestaurants
     */
    public function setPostTown($postTown)
    {
        $this->postTown = $postTown;

        return $this;
    }

    /**
     * Get postTown
     *
     * @return string
     */
    public function getPostTown()
    {
        return $this->postTown;
    }

    /**
     * Set adminRegion
     *
     * @param string $adminRegion
     *
     * @return GlobalRestaurants
     */
    public function setAdminRegion($adminRegion)
    {
        $this->adminRegion = $adminRegion;

        return $this;
    }

    /**
     * Get adminRegion
     *
     * @return string
     */
    public function getAdminRegion()
    {
        return $this->adminRegion;
    }

    /**
     * Set postcode
     *
     * @param integer $postcode
     *
     * @return GlobalRestaurants
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return integer
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return GlobalRestaurants
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return GlobalRestaurants
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return GlobalRestaurants
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return GlobalRestaurants
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return GlobalRestaurants
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set neighborhood
     *
     * @param string $neighborhood
     *
     * @return GlobalRestaurants
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get neighborhood
     *
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return GlobalRestaurants
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return GlobalRestaurants
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set categoryIds
     *
     * @param string $categoryIds
     *
     * @return GlobalRestaurants
     */
    public function setCategoryIds($categoryIds)
    {
        $this->categoryIds = $categoryIds;

        return $this;
    }

    /**
     * Get categoryIds
     *
     * @return string
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * Set categoryLabels
     *
     * @param string $categoryLabels
     *
     * @return GlobalRestaurants
     */
    public function setCategoryLabels($categoryLabels)
    {
        $this->categoryLabels = $categoryLabels;

        return $this;
    }

    /**
     * Get categoryLabels
     *
     * @return string
     */
    public function getCategoryLabels()
    {
        return $this->categoryLabels;
    }

    /**
     * Set chainName
     *
     * @param string $chainName
     *
     * @return GlobalRestaurants
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
     * Set chainId
     *
     * @param string $chainId
     *
     * @return GlobalRestaurants
     */
    public function setChainId($chainId)
    {
        $this->chainId = $chainId;

        return $this;
    }

    /**
     * Get chainId
     *
     * @return string
     */
    public function getChainId()
    {
        return $this->chainId;
    }

    /**
     * Set hours
     *
     * @param string $hours
     *
     * @return GlobalRestaurants
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return string
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set hoursDisplay
     *
     * @param string $hoursDisplay
     *
     * @return GlobalRestaurants
     */
    public function setHoursDisplay($hoursDisplay)
    {
        $this->hoursDisplay = $hoursDisplay;

        return $this;
    }

    /**
     * Get hoursDisplay
     *
     * @return string
     */
    public function getHoursDisplay()
    {
        return $this->hoursDisplay;
    }

    /**
     * Set existence
     *
     * @param string $existence
     *
     * @return GlobalRestaurants
     */
    public function setExistence($existence)
    {
        $this->existence = $existence;

        return $this;
    }

    /**
     * Get existence
     *
     * @return string
     */
    public function getExistence()
    {
        return $this->existence;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return GlobalRestaurants
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set zoomOrder
     *
     * @param boolean $zoomOrder
     *
     * @return GlobalRestaurants
     */
    public function setZoomOrder($zoomOrder)
    {
        $this->zoomOrder = $zoomOrder;

        return $this;
    }

    /**
     * Get zoomOrder
     *
     * @return boolean
     */
    public function getZoomOrder()
    {
        return $this->zoomOrder;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return GlobalRestaurants
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

    /**
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return GlobalRestaurants
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Get lastModified
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set fromSource
     *
     * @param string $fromSource
     *
     * @return GlobalRestaurants
     */
    public function setFromSource($fromSource)
    {
        $this->fromSource = $fromSource;

        return $this;
    }

    /**
     * Get fromSource
     *
     * @return string
     */
    public function getFromSource()
    {
        return $this->fromSource;
    }

    /**
     * Set avgRating
     *
     * @param float $avgRating
     *
     * @return GlobalRestaurants
     */
    public function setAvgRating($avgRating)
    {
        $this->avgRating = $avgRating;

        return $this;
    }

    /**
     * Get avgRating
     *
     * @return float
     */
    public function getAvgRating()
    {
        return $this->avgRating;
    }

    /**
     * Set nbVotes
     *
     * @param integer $nbVotes
     *
     * @return GlobalRestaurants
     */
    public function setNbVotes($nbVotes)
    {
        $this->nbVotes = $nbVotes;

        return $this;
    }

    /**
     * Get nbVotes
     *
     * @return integer
     */
    public function getNbVotes()
    {
        return $this->nbVotes;
    }

    /**
     * Set avgPrice
     *
     * @param integer $avgPrice
     *
     * @return GlobalRestaurants
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
     * Set mapImage
     *
     * @param string $mapImage
     *
     * @return GlobalRestaurants
     */
    public function setMapImage($mapImage)
    {
        $this->mapImage = $mapImage;

        return $this;
    }

    /**
     * Get mapImage
     *
     * @return string
     */
    public function getMapImage()
    {
        return $this->mapImage;
    }

    /**
     * Set updated
     *
     * @param boolean $updated
     *
     * @return GlobalRestaurants
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return boolean
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
