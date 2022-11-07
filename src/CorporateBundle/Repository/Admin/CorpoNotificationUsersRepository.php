<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoNotificationUsers;

class CorpoNotificationUsersRepository extends EntityRepository
{
    protected $utils;
    protected $em;


    /**
     * This method will add a notification by users
     * @param $notificationUserObj
     * @return doctrine result of notification id or false in case of no data
     * */
    public function addNotificationUsers($notificationUserObj)
    {
        $this->em = $this->getEntityManager();
        $notificationUsers  = new CorpoNotificationUsers();

        $userId = $notificationUserObj->getUser()->getId();
        $accountId = $notificationUserObj->getAccount()->getId();
        $notificationId = $notificationUserObj->getNotification()->getId();

        if (isset($userId) && $userId != '') {
            $notificationUsers->setUserId($userId);
        }
        if (isset($accountId) && $accountId != '') {
            $notificationUsers->setAccountId($accountId);
        }
        if (isset($notificationId) && $notificationId != '') {
            $notificationUsers->setNotificationId($notificationId);
        }

        $this->em->persist($notificationUsers);
        $this->em->flush();
        if ($notificationUsers) {
            return $notificationUsers->getId();
        } else {
            return false;
        }
    }
    /**
     * This method will update a notification user
     * @param id of account
     * @return doctrine object result of notification user or false in case of no data
     * */
    public function updateNotificationUsers($notificationUserObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoNotificationUsers', 'ca');

        $userId = $notificationUserObj->getUser()->getId();
        $accountId = $notificationUserObj->getAccount()->getId();

        if (isset($userId)) {
            $qb->set("ca.userId", ":userId")
            ->setParameter(':userId', $userId);
        }
        if (isset($accountId)) {
            $qb->set("ca.accountId", ":accountId")
            ->setParameter(':accountId', $accountId);
        }

        $qb->where("ca.notificationId=:notificationId")
            ->setParameter(':notificationId', $notificationUserObj->getNotification()->getId());

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete a notification User
     * @param id of notification
     * @return doctrine object result of notification User or false in case of no data
     * */
    public function deleteNotificationUsers($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->delete('CorporateBundle:CorpoNotificationUsers', 'ca')
            ->where("ca.notificationId = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}