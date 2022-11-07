<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoNotification;
use TTBundle\Utils\Utils;

class CorpoNotificationRepository extends EntityRepository
{
    protected $utils;
    protected $em;
   
    /**
     * This method will retrieve all notifications
     * @param
     * @return doctrine object result of notifications or false in case of no data
     * */
    public function getCorpoNotificationList($parameters)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p, u.fullname as userName, u.id as userId, a.name as accountName, a.id as accountId, nt.name as typeName')
            ->leftJoin('CorporateBundle:CorpoNotificationUsers', 'nu', 'WITH', "p.id = nu.notificationId")
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = nu.accountId")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = nu.userId")
            ->leftJoin('CorporateBundle:CorpoNotificationType', 'nt', 'WITH', "nt.id = p.typeId");
        
        if(isset($parameters['notificationId']) && $parameters['notificationId'] != ''){
           $query->where("p.id = :notificationId")
                ->setParameter(':notificationId', $parameters['notificationId']);
        }
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    public function prepareNotificationsDtQuery()
    {
        $query = "
            SELECT cn.id cn__id, cnt.name cnt__name, subject, FullName, ca.name ca__name, notification_date FROM corpo_notification cn
            LEFT JOIN corpo_notification_type cnt ON (cnt.id = cn.type_id)
            LEFT JOIN corpo_notification_users cnu ON (cnu.notification_id = cn.id)
            LEFT JOIN cms_users cu ON (cu.id = cnu.user_id)
            LEFT JOIN corpo_account ca ON (ca.id = cnu.account_id)
        ";
        $result_arr["all_query"] = $query;
        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will add a notification
     * @param $notificationObj
     * @return doctrine result of notification id or false in case of no data
     * */
    public function addNotification($notificationObj)
    {
        $this->em = $this->getEntityManager();
        $notification  = new CorpoNotification();

        $subject = $notificationObj->getSubject();
        $mssg = $notificationObj->getMssg();
        $typeId = $notificationObj->getType()->getId();
        $date = $notificationObj->getNotificationDate();
        $createdBy = $notificationObj->getCreatedBy()->getId();

        if (isset($subject) && $subject != '') {
            $notification->setSubject($subject);
        }
        if (isset($mssg) && $mssg != '') {
            $notification->setMssg($mssg);
        }
        if (isset($typeId) && $typeId != '') {
            $notification->setTypeId($typeId);
        }
        if (isset($date) && $date != '') {
            $notification->setNotificationDate($date);
        }
        $notification->setNotificationIsSent(0);
        $notification->setCreatedAt(new \DateTime("now"));
        $notification->setCreatedBy($createdBy);

        $this->em->persist($notification);
        $this->em->flush();
        if ($notification) {
            return $notification->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update a notification
     * @param $notificationObj
     * @return doctrine object result of notification or false in case of no data
     * */
    public function updateNotification($notificationObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoNotification', 'ca');

        $id = $notificationObj->getId();
        $subject = $notificationObj->getSubject();
        $mssg = $notificationObj->getMssg();
        $typeId = $notificationObj->getType()->getId();
        $date = $notificationObj->getNotificationDate();

        if (isset($subject) && $subject != '') {
            $qb->set("ca.subject", ":subject")
            ->setParameter(':subject', $subject);
        }
        if (isset($mssg) && $mssg != '') {
            $qb->set("ca.mssg", ":mssg")
            ->setParameter(':mssg', $mssg);
        }
        if (isset($typeId) && $typeId != '') {
            $qb->set("ca.typeId", ":typeId")
            ->setParameter(':typeId', $typeId);
        }
        if (isset($date) && $date != '') {
            $qb->set("ca.notificationDate", ":notificationDate")
            ->setParameter(':notificationDate', $date);
        }

        $qb->where("ca.id=:Id")
            ->setParameter(':Id', $id);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete a notification
     * @param id of notification
     * @return doctrine object result of notification or false in case of no data
     * */
    public function deleteNotification($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->delete('CorporateBundle:CorpoNotification', 'ca')
            ->where("ca.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}