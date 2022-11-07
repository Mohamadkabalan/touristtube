<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatastellarHotelImage
 *
 * @ORM\Table(name="datastellar_hotel_image", uniqueConstraints={@ORM\UniqueConstraint(name="title", columns={"title"}), @ORM\UniqueConstraint(name="path", columns={"path"})}, indexes={@ORM\Index(name="hotel_id", columns={"hotel_id"}), @ORM\Index(name="dir", columns={"dir"}), @ORM\Index(name="dir_2", columns={"dir"})})
 * @ORM\Entity
 */
class DatastellarHotelImage
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="dir", type="string", length=255, nullable=false)
     */
    private $dir;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @var integer
     *
     * @ORM\Column(name="file_size_bytes", type="integer", nullable=false)
     */
    private $fileSizeBytes;

    /**
     * @var integer
     *
     * @ORM\Column(name="width_px", type="integer", nullable=false)
     */
    private $widthPx;

    /**
     * @var integer
     *
     * @ORM\Column(name="height_px", type="integer", nullable=false)
     */
    private $heightPx;

    /**
     * @var integer
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=false)
     */
    private $hotelId;



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
     * @return DatastellarHotelImage
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
     * Set dir
     *
     * @param string $dir
     *
     * @return DatastellarHotelImage
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get dir
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return DatastellarHotelImage
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set fileSizeBytes
     *
     * @param integer $fileSizeBytes
     *
     * @return DatastellarHotelImage
     */
    public function setFileSizeBytes($fileSizeBytes)
    {
        $this->fileSizeBytes = $fileSizeBytes;

        return $this;
    }

    /**
     * Get fileSizeBytes
     *
     * @return integer
     */
    public function getFileSizeBytes()
    {
        return $this->fileSizeBytes;
    }

    /**
     * Set widthPx
     *
     * @param integer $widthPx
     *
     * @return DatastellarHotelImage
     */
    public function setWidthPx($widthPx)
    {
        $this->widthPx = $widthPx;

        return $this;
    }

    /**
     * Get widthPx
     *
     * @return integer
     */
    public function getWidthPx()
    {
        return $this->widthPx;
    }

    /**
     * Set heightPx
     *
     * @param integer $heightPx
     *
     * @return DatastellarHotelImage
     */
    public function setHeightPx($heightPx)
    {
        $this->heightPx = $heightPx;

        return $this;
    }

    /**
     * Get heightPx
     *
     * @return integer
     */
    public function getHeightPx()
    {
        return $this->heightPx;
    }

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     *
     * @return DatastellarHotelImage
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
}
