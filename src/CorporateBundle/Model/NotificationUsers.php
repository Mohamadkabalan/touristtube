<?php

namespace CorporateBundle\Model;

use TTBundle\Utils\Utils;
use TTBundle\Model\User;

class NotificationUsers {
    
    private $id;

    private $account;

    private $user;

    private $notification;

    public function __construct() {
        $this->account = new Account();
        $this->user = new User();
        $this->notification = new Notification();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function arrayToObject($params)
    {
        $notificationUsers = new NotificationUsers();
        if (!empty($params)) {
            /**'Code' suffix appended by TTAutocomplete */
            if($params['id']) {
                if($params['accountCode'] != ''){
                    $notificationUsers->getAccount()->setId($params['accountCode']);
                }

                if($params['userCode'] != '') {
                    $notificationUsers->getUser()->setId($params['userCode']);
                }
            } else {
                $notificationUsers->getAccount()->setId($params['accountCode']);

                if($params['userCode'] != '') {
                    $notificationUsers->getUser()->setId($params['userCode']);
                } else {
                    $notificationUsers->getUser()->setId($params['createdBy']);
                }
            }

            $notificationUsers->getNotification()->setId(isset($params['notificationId']) ? $params['notificationId'] : 0);
        }
        return Utils::array_to_obj($params,$notificationUsers);
    }
}