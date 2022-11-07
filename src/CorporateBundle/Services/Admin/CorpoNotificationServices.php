<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorpoNotificationServices
{
    protected $utils;
    protected $em;
    protected $CorpoAdminServices;
    protected $container;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container)
    {
        $this->utils                = $utils;
        $this->em                   = $em;
        $this->container            = $container;

    }

    /**
     * getting from the repository all Notification
     *
     * @return list
     */
    public function getCorpoNotificationList($parameters)
    {
        $notificationList = $this->em->getRepository('CorporateBundle:CorpoNotification')->getCorpoNotificationList($parameters);
        return $notificationList;
    }

    public function prepareNotificationsDtQuery()
    {
        return $this->em->getRepository('CorporateBundle:CorpoNotification')->prepareNotificationsDtQuery();
    }

    /**
     * adding a notification
     *
     * @param $notificationObj object
     * @return integer
     */
    public function addNotification($notificationObj)
    {
        $notificationId = $this->em->getRepository('CorporateBundle:CorpoNotification')->addNotification($notificationObj);
        return $notificationId;
    }

    /**
     * adding a notification user
     *
     * @param $notificationUserObj object
     * @return list
     */
    public function addNotificationUser($notificationUserObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoNotificationUsers')->addNotificationUsers($notificationUserObj);
        return $addResult;
    }

    /**
     * getting from the repository all Notification
     *
     * @return list
     */
    public function getCorpoNotificationTypeList()
    {
        $notificationTypeList = $this->em->getRepository('CorporateBundle:CorpoNotificationType')->getCorpoNotificationTypeList();
        return $notificationTypeList;
    }

    /**
     * updating a Notification
     *
     * @return list
     */
    public function updateNotification($notificationObj)
    {
        $success = $this->em->getRepository('CorporateBundle:CorpoNotification')->updateNotification($notificationObj);
        return $success;
    }

    /**
     * updating a NotificationUser
     *
     * @return list
     */
    public function updateNotificationUser($notificationUserObj)
    {
        $success = $this->em->getRepository('CorporateBundle:CorpoNotificationUsers')->updateNotificationUsers($notificationUserObj);
        return $success;
    }
    
    /**
     * deleting a Notification
     *
     * @return list
     */
    public function deleteNotification($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoNotification')->deleteNotification($id);
        if($addResult){
            $addResult = $this->em->getRepository('CorporateBundle:CorpoNotificationUsers')->deleteNotificationUsers($id);
            return $addResult;
        }else{
            return false;
        }
    }
}