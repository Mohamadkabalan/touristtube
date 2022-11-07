<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;

class CorpoDepartmentsServices
{
    protected $utils;
    protected $em;
    protected $CorpoAdminServices;

    public function __construct(Utils $utils, EntityManager $em, CorpoAdminServices $CorpoAdminServices)
    {
        $this->utils     = $utils;
        $this->em        = $em;
        $this->CorpoAdminServices = $CorpoAdminServices;
    }

    /**
     * getting from the repository all Departments
     *
     * @return list
     */
    public function getCorpoAdminDepartmentsList()
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId      = $sessionInfo['accountId'];
        $profilesList = $this->em->getRepository('CorporateBundle:CorpoDepartments')->getDepartmentsList($accountId);
        return $profilesList;
    }

    /**
     * getting from the repository all departments
     *
     * @return list
     */
    public function getCorpoAdminLikeDepartments($term, $limit)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        $departmentList = $this->em->getRepository('CorporateBundle:CorpoDepartments')->getCorpoAdminLikeDepartments($term, $limit, $accountId);
        return $departmentList;
    }

    /**
     * getting from the repository a Department
     *
     * @return list
     */
    public function getCorpoAdminDepartment($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoDepartments')->getDepartmentById($id);
        return $account;
    }

    /**
     * adding a Department
     *
     * @return list
     */
    public function addDepartments($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if(!isset($parameters['accountId']) && !isset($parameters['accountNameCode'])){
            $parameters['accountId'] = $sessionInfo['accountId'];
        }elseif(isset($parameters['accountNameCode'])){
            $parameters['accountId'] = $parameters['accountNameCode'];
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoDepartments')->addDepartments($parameters);
        return $addResult;
    }

    /**
     * deleting a Department
     *
     * @return list
     */
    public function deleteDepartments($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoDepartments')->deleteDepartments($id);
        return $addResult;
    }

    /**
     * updating a Department
     *
     * @return list
     */
    public function updateDepartments($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if(!isset($parameters['accountId'])){
            $parameters['accountId'] = $sessionInfo['accountId'];
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoDepartments')->updateDepartments($parameters);
        return $addResult;
    }
}