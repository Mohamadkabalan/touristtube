<?php

namespace DealBundle\Model;

/**
 * DealNote contains the frequently asked questions from activitiyDetails call
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealDirections
{
    private $title       = '';
    private $description = '';
    private $image       = '';

    /**
     * Get title
     * @return String
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * Get description
     * @return String
     */
    function getDescription()
    {
        return $this->description;
    }

    /**
     * Get image
     * @return String
     */
    function getImage()
    {
        return $this->image;
    }

    /**
     * Set title
     * @param String $title
     */
    function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set description
     * @param String $description
     */
    function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set image
     * @param String $image
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