<?php

namespace HotelBundle\Model;

/**
 * The HotelCancellationForm class
 *
 *
 */
class HotelCancellationForm
{
    private $userId        = 0;
    private $reference     = '';
    private $controlNumber = '';

    /**
     * The rooms to cancel.
     * @var HotelRoomCancellation[]
     */
    private $rooms = array();

    /**
     * Get userId.
     * @return Integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get reference.
     * @return String
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Get controlNumber.
     * @return String
     */
    public function getControlNumber()
    {
        return $this->controlNumber;
    }

    /**
     * Get HotelRoomCancellation[] rooms.
     * @return HotelRoomCancellation[]
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set userId.
     * @param Integer $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Set reference.
     * @param String $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * Set controlNumber.
     * @param String $controlNumber
     * @return $this
     */
    public function setControlNumber($controlNumber)
    {
        $this->controlNumber = $controlNumber;
        return $this;
    }

    /**
     * Add a room to cancel.
     * @param \HotelBundle\Model\HotelRoomCancellationForm $room
     * @return $this
     */
    public function addRoom(HotelRoomCancellationForm $room)
    {
        $this->rooms[] = $room;
        return $this;
    }
}
