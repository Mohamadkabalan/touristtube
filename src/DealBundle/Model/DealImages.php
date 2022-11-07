<?php

namespace DealBundle\Model;

/**
 * DealImage
 * This is used in Image for Deals
 *
 * @author Anna Lou Parejo <anna.parejo@touristtube.com>
 */
class DealImages
{
    private $directory   = '';
    private $imageId     = '';
    private $isThumbnail = '';
    private $imgWidth    = '';
    private $imgHeight   = '';
    private $image       = '';

    /**
     * 
     */
    private $commonSC;

    /**
     * The __construct
     */
    public function __construct()
    {
        $this->commonSC = new DealsCommonSC();
    }

    /**
     * Get Common search criteria object
     * @return DealsCommonSC object
     */
    function getCommonSC()
    {
        return $this->commonSC;
    }

    /**
     * Get directory
     * @return string directory
     */
    function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get image
     * @return integer imageId
     */
    function getImageId()
    {
        return $this->imageId;
    }

    /**
     * Get isThumbnail
     * @return boolean isThumbnail
     */
    function getIsThumbnail()
    {
        return $this->isThumbnail;
    }

    /**
     * Get imgWidth
     * @return decimal imgWidth
     */
    function getImgWidth()
    {
        return $this->imgWidth;
    }

    /**
     * Get imgHeight
     * @return decimal imgHeight
     */
    function getImgHeight()
    {
        return $this->imgHeight;
    }

    /**
     * Get image
     * @return string image
     */
    function getImage()
    {
        return $this->image;
    }

    /**
     * Set directory
     * @param string directory
     */
    function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Set imageId
     * @param Integer imageId
     */
    function setImageId($imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     * Set isThumbnail
     * @param boolean isThumbnail
     */
    function setIsThumbnail($isThumbnail)
    {
        $this->isThumbnail = $isThumbnail;
    }

    /**
     * Set imgWidth
     * @param decimal imgWidth
     */
    function setImgWidth($imgWidth)
    {
        $this->imgWidth = $imgWidth;
    }

    /**
     * Set imgHeight
     * @param decimal imgHeight
     */
    function setImgHeight($imgHeight)
    {
        $this->imgHeight = $imgHeight;
    }

    /**
     * Set image
     * @param string image
     */
    function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get array format response of this instance
     * @return Array
     */
    public function toArray()
    {
        $toreturn = array();
        foreach ($this as $key => $value) {
            $toreturn[$key] = $value;
        }
        return $toreturn;
    }
}