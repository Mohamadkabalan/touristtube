<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\User;

class Notification {
    
    private $id;

    private $subject;

    private $mssg;

    private $type;

    private $notificationDate;

    
    private $createdBy;

    public function __construct() {
        $this->type = new NotificationType();
        $this->createdBy = new User();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getMssg() {
        return $this->mssg;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getNotificationDate()
    {
        return $this->notificationDate;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setMssg($mssg)
    {
        $this->mssg = $mssg;
    }

    public function setNotificationDate($date)
    {
        $this->notificationDate = new \DateTime($date);
    }

    public function arrayToObject($params)
    {
        $notification = new Notification();
        if (!empty($params)) {
            $notification->getType()->setId(isset($params['typeId']) ? $params['typeId'] : 1);
            $notification->getCreatedBy()->setId(isset($params['createdBy']) ? $params['createdBy'] : 1);
        }
        return Utils::array_to_obj($params,$notification);
    }
}