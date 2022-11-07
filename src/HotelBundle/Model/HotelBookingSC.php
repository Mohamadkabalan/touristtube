<?php

namespace HotelBundle\Model;

/**
 * Description of HotelBookingSC
 *
 * @author Mica
 */
class HotelBookingSC
{
    private $userId;
    private $userEmail;
    private $fromDate;
    private $toDate;
    private $bookingStatus = 0; // 0 = all, 1 = past, 2 = canceled, 3 = future
    private $page          = 0;
    private $showMore      = 0;
    private $isRest        = 0;

    public function getPage()
    {
        return $this->page;
    }

    public function getShowMore()
    {
        return $this->showMore;
    }

    public function getIsRest()
    {
        return $this->isRest;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function setShowMore($showMore)
    {
        $this->showMore = $showMore;
    }

    public function setIsRest($isRest)
    {
        $this->isRest = $isRest;
    }

    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    public function getBookingStatus()
    {
        return $this->bookingStatus;
    }

    public function setBookingStatus($bookingStatus)
    {
        $this->bookingStatus = $bookingStatus;
    }
}
