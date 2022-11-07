<?php

namespace DealBundle\Model;

/**
 * DealStartingPlace contains the schedules from activitiyDetails call
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealSchedule
{
    private $order       = '';
    private $title       = '';
    private $time        = '';
    private $description = '';
    private $groupId     = 0;

    /**
     * Get order
     * @return String
     */
    function getOrder()
    {
        return $this->order;
    }

    /**
     * Get title
     * @return String
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * Get time
     * @return String
     */
    function getTime()
    {
        return $this->time;
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
     * Get groupId
     * @return Integer
     */
    function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set order
     * @param String $order
     */
    function setOrder($order)
    {
        $this->order = $order;
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
     * Set time
     * @param String $time
     */
    function setTime($time)
    {
        $this->time = $time;
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
     * Set groupId
     * @param Integer $groupId
     */
    function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }
}