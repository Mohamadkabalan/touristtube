<?php

namespace RestBundle\Model;

class RestBookingResponseVO
{
    private $success;
    private $message;
    private $hotelsBookingVO;
    private $flightsBookingVO;
    private $dealsBookingVO;

    /**
     * Get success
     * @return boolean
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Set success
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * Get message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     * @param integer $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get HotelsBookingVO
     * @return
     */
    public function getHotelsBookingVO()
    {
        return $this->hotelsBookingVO;
    }

    /**
     * Set HotelsBookingVO
     * @param $hotelsBookingVO
     */
    public function setHotelsBookingVO($hotelsBookingVO)
    {
        $this->hotelsBookingVO = $hotelsBookingVO;
    }

    /**
     * Get FlightsBookingVO
     * @return
     */
    public function getFlightsBookingVO()
    {
        return $this->flightsBookingVO;
    }

    /**
     * Set FlightsBookingVO
     * @param $flightsBookingVO
     */
    public function setFlightsBookingVO($flightsBookingVO)
    {
        $this->flightsBookingVO = $flightsBookingVO;
    }

    /**
     * Get DealsBookingVO
     * @return
     */
    public function getDealsBookingVO()
    {
        return $this->dealsBookingVO;
    }

    /**
     * Set DealsBookingVO
     * @param $dealsBookingVO
     */
    public function setDealsBookingVO($dealsBookingVO)
    {
        $this->dealsBookingVO = $dealsBookingVO;
    }
}
