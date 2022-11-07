<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoNotificationType;

class CorpoNotificationTypeRepository extends EntityRepository
{
    protected $utils;
    protected $em;


    /**
     * This method will get the list of notification type
     * @param 
     * @return doctrine result of notification types or false in case of no data
     * */
    public function getCorpoNotificationTypeList()
    {
        $query = $this->createQueryBuilder('t')
            ->select('t');
        
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
}