<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AirportImages
 *
 * @ORM\Table(name="airport_images")
 * @ORM\Entity
 */
class AirportImages
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="airport_id", type="integer", nullable=false)
     */
    private $airportId;

    /**
     * @var integer
     *
     * @ORM\Column(name="default_pic", type="integer", nullable=false)
     */
    private $defaultPic = '0';



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
     * Set userId
     *
     * @param integer $userId
     *
     * @return AirportImages
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return AirportImages
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set airportId
     *
     * @param integer $airportId
     *
     * @return AirportImages
     */
    public function setAirportId($airportId)
    {
        $this->airportId = $airportId;

        return $this;
    }

    /**
     * Get airportId
     *
     * @return integer
     */
    public function getAirportId()
    {
        return $this->airportId;
    }

    /**
     * Set defaultPic
     *
     * @param integer $defaultPic
     *
     * @return AirportImages
     */
    public function setDefaultPic($defaultPic)
    {
        $this->defaultPic = $defaultPic;

        return $this;
    }

    /**
     * Get defaultPic
     *
     * @return integer
     */
    public function getDefaultPic()
    {
        return $this->defaultPic;
    }
}
