<?php

namespace HotelBundle\Model;

/**
 * Description of Hotel360ThingsToDo
 *
 */
class Hotel360ThingsToDo
{
    private $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
