<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoPaymentType;

class CorpoPaymentTypeRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all payments type of corporate
     * @param
     * @return doctrine object result of payments type or false in case of no data
     * */
    public function getAccountPaymentList()
    {
        $query = $this->createQueryBuilder('ap')
            ->select('ap');

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
}