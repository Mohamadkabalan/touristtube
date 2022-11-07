<?php

namespace HotelBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * HotelRoomReservation
 *
 * @ORM\Table(name="hotel_room_reservation")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\HotelRepository")
 */
class HotelRoomReservation
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
     * @ORM\Column(name="hotel_reservation_id", type="integer")
     */
    private $hotelReservationId;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reservation_process_key", type="string", nullable=true)
     */
    private $reservationProcessKey;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reservation_process_password", type="string", nullable=true)
     */
    private $reservationProcessPassword;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reservation_key", type="string", nullable=true)
     */
    private $reservationKey;

    /**
     * @var decimal
     *
     * @ORM\Column(name="hotel_room_price", type="decimal", nullable=false)
     */
    private $hotelRoomPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="customer_room_price", type="decimal", nullable=false)
     */
    private $customerRoomPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="guests", type="text", length=65535, nullable=false)
     */
    private $guests;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="room_status", type="string", options={"comment":"Options: 'Confirmed', 'Modified', 'Canceled'"}, nullable=false)
     */
    private $roomStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="roomInfo", type="text", length=65535, nullable=true)
     */
    private $roomInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="roomOfferDetail", type="text", length=65535, nullable=true)
     */
    private $roomOfferDetail;

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
     * Set hotel reservation ID
     *
     * @param integer $hotelReservationId
     */
    public function setHotelReservationId($hotelReservationId)
    {
        $this->hotelReservationId = $hotelReservationId;
    }

    /**
     * Get hotel reservation ID
     *
     * @return integer
     */
    public function getHotelReservationId()
    {
        return $this->hotelReservationId;
    }

    /**
     * Set Reservation Process Key
     *
     * @param string $reservationProcessKey
     */
    public function setReservationProcessKey($reservationProcessKey)
    {
        $this->reservationProcessKey = $reservationProcessKey;
    }

    /**
     * Get Reservation Process Key
     *
     * @return string
     */
    public function getReservationProcessKey()
    {
        return $this->reservationProcessKey;
    }

    /**
     * Set Reservation Process Password
     *
     * @param string $reservationProcessPassword
     */
    public function setReservationProcessPassword($reservationProcessPassword)
    {
        $this->reservationProcessPassword = $reservationProcessPassword;
    }

    /**
     * Get Reservation Process Password
     *
     * @return string
     */
    public function getReservationProcessPassword()
    {
        return $this->reservationProcessPassword;
    }

    /**
     * Set Reservation Key
     *
     * @param string $reservationKey
     */
    public function setReservationKey($reservationKey)
    {
        $this->reservationKey = $reservationKey;
    }

    /**
     * Get Reservation Key
     *
     * @return string
     */
    public function getReservationKey()
    {
        return $this->reservationKey;
    }

    /**
     * Get hotelRoomPrice
     *
     * @return decimal
     */
    public function getHotelRoomPrice()
    {
        return $this->hotelRoomPrice;
    }

    /**
     * Set hotelRoomPrice
     *
     * @param decimal $hotelRoomPrice
     */
    public function setHotelRoomPrice($hotelRoomPrice)
    {
        $this->hotelRoomPrice = $hotelRoomPrice;
    }

    /**
     * Get customerRoomPrice
     *
     * @return decimal
     */
    public function getCustomerRoomPrice()
    {
        return $this->customerRoomPrice;
    }

    /**
     * Set customerRoomPrice
     *
     * @param decimal $customerRoomPrice
     */
    public function setCustomerRoomPrice($customerRoomPrice)
    {
        $this->customerRoomPrice = $customerRoomPrice;
    }

    /**
     * Set guests
     *
     * @param string $guests
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;
    }

    /**
     * Get guests
     *
     * @return string
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Set room status
     *
     * @param string $roomStatus
     */
    public function setRoomStatus($roomStatus)
    {
        $this->roomStatus = $roomStatus;
    }

    /**
     * Get room status
     *
     * @return string
     */
    public function getRoomStatus()
    {
        return $this->roomStatus;
    }

    /**
     * Set roomInfo
     *
     * @param string $roomInfo
     */
    public function setRoomInfo($roomInfo)
    {
        $this->roomInfo = $roomInfo;
    }

    /**
     * Get roomInfo
     *
     * @return string
     */
    public function getRoomInfo()
    {
        return $this->roomInfo;
    }

    /**
     * Set roomOfferDetail
     *
     * @param string $roomOfferDetail
     */
    public function setRoomOfferDetail($roomOfferDetail)
    {
        $this->roomOfferDetail = $roomOfferDetail;
    }

    /**
     * Get roomOfferDetail
     *
     * @return string
     */
    public function getRoomOfferDetail()
    {
        return $this->roomOfferDetail;
    }
}