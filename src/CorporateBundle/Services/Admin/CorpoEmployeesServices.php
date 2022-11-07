<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;

class CorpoEmployeesServices
{
    protected $utils;
    protected $em;

    public function __construct(Utils $utils, EntityManager $em, CorpoAdminServices $CorpoAdminServices)
    {
        $this->utils     = $utils;
        $this->em        = $em;
        $this->CorpoAdminServices = $CorpoAdminServices;
    }

    /**
     * getting from the repository all Employees
     *
     * @return list
     */
    public function getEmployeeAllList()
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId      = $sessionInfo['accountId'];
        $employeeList = $this->em->getRepository('CorporateBundle:CorpoEmployees')->getEmployeeAllList($accountId);
        return $employeeList;
    }

    /**
     * getting from the repository all Employees
     *
     * @return list
     */
    public function getCorpoAdminEmployeeList()
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId      = $sessionInfo['accountId'];
        $employeeList = $this->em->getRepository('CorporateBundle:CorpoEmployees')->getEmployeeList($accountId);
        return $employeeList;
    }

    /**
     * getting from the repository an Employee
     *
     * @return list
     */
    public function getCorpoAdminEmployeeById($parameters)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoEmployees')->getEmployeeById($parameters);
        return $account;
    }

    /**
     * adding an Employee
     *
     * @return list
     */
    public function addEmployee($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if(!isset($parameters['accountId'])){
            $parameters['accountId'] = $sessionInfo['accountId'];
        }
        if(!isset($parameters['userId'])){
            $parameters['userId'] = $sessionInfo['userId'];
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoEmployees')->addEmployee($parameters);
        return $addResult;
    }

    /**
     * deleting an Employee
     *
     * @return list
     */
    public function deleteEmployee($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoEmployees')->deleteEmployee($id);
        return $addResult;
    }

    /**
     * updating an Employee
     *
     * @return list
     */
    public function updateEmployee($parameters)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoEmployees')->updateEmployee($parameters);
        return $addResult;
    }
}