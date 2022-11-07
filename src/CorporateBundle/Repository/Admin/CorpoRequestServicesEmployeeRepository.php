<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoRequestServicesEmployee;

class CorpoRequestServicesEmployeeRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will add a Request Service Employee
     * @param Request Service Employee info list
     * @return doctrine result of Request Service Employee id or false in case of no data
     * */
    public function addRequestServicesEmployee($id, $employeeId)
    {
        $this->em                = $this->getEntityManager();
        $requestServicesEmployee = new CorpoRequestServicesEmployee();
        $requestServicesEmployee->setEmployeeId($employeeId);
        $requestServicesEmployee->setRequestId($id);
        $this->em->persist($requestServicesEmployee);
        $this->em->flush();
        if ($requestServicesEmployee) {
            return $requestServicesEmployee->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will delete a Request Service Employee 
     * @param id of Profile
     * @return doctrine object result of Profile or false in case of no data
     * */
    public function deleteRequestServicesEmployee($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoRequestServicesEmployee', 'p')
            ->where("p.requestId = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will retrieve a Request Service Employee of corporate
     * @param id of Request Service Employee
     * @return doctrine object result of Request Service Employee  or false in case of no data
     * */
    public function getRequestEmployeeList($id)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e.employeeId')
            ->where("e.requestId = :ID")
            ->setParameter(':ID', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
}