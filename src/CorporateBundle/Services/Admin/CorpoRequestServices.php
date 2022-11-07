<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;

class CorpoRequestServices
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
     * getting from the repository all Request Services
     *
     * @return list
     */
    public function getCorpoAdminRequestServicesList()
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId      = $sessionInfo['accountId'];
        $requestServicesList = $this->em->getRepository('CorporateBundle:CorpoRequestServices')->getRequestServicesList($accountId);
        return $requestServicesList;
    }

    /**
     * getting from the repository a Request Service
     *
     * @return list
     */
    public function getCorpoAdminRequestServiceIdList($id)
    {
        $requestServicesList = $this->em->getRepository('CorporateBundle:CorpoRequestServices')->getRequestServiceList($id);
        if (is_null($requestServicesList['p_isApproved']) || $requestServicesList['p_isApproved'] == '') {
            $isApproved = 1;
        } else {
            $isApproved = 0;
        }
        $requestServicesList['p_isApproved'] = $isApproved;
        return $requestServicesList;
    }

    /**
     * getting from the repository a Request Service Items list
     *
     * @return list
     */
    public function getRequestItemsList($id)
    {
        $requestServicesList = $this->em->getRepository('CorporateBundle:CorpoRequestServicesItems')->getRequestItemsList($id);
        return $requestServicesList;
    }

    /**
     * getting from the repository a Request Service Employees list
     *
     * @return list
     */
    public function getRequestEmployeeList($id)
    {
        $requestServicesList = $this->em->getRepository('CorporateBundle:CorpoRequestServicesEmployee')->getRequestEmployeeList($id);
        return $requestServicesList;
    }

    /**
     * getting from the repository a Request Service Employee
     *
     * @return list
     */
    public function getCorpoAdminEmployee($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoRequestServices')->getEmployeeById($id);
        return $account;
    }

    /**
     * adding a Request Service
     *
     * @return list
     */
    public function addRequestServices($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if(!isset($parameters['accountId'])){
            $parameters['accountId'] = $sessionInfo['accountId'];
        }
        if (array_key_exists('isApproved', $parameters)) {
            $isApproved = 1;
        } else {
            $isApproved = 0;
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoRequestServices')->addRequestServices($parameters, $isApproved);
        return $addResult;
    }

    /**
     * deleting a Request Service
     *
     * @return list
     */
    public function deleteRequestServices($id)
    {
        $success1 = $this->em->getRepository('CorporateBundle:CorpoRequestServices')->deleteRequestServices($id);
        $success2 = $this->em->getRepository('CorporateBundle:CorpoRequestServicesEmployee')->deleteRequestServicesEmployee($id);
        $success3 = $this->em->getRepository('CorporateBundle:CorpoRequestServicesItems')->deleteRequestServicesItems($id);
        return $success3;
    }

    /**
     * updating a Request Service
     *
     * @return list
     */
    public function updateRequestServices($parameters)
    {
        $id = $parameters['id'];
        if (array_key_exists('isApproved', $parameters)) {
            $isApproved = 1;
        } else {
            $isApproved = 0;
        }
        $addResult      = $this->em->getRepository('CorporateBundle:CorpoRequestServices')->updateRequestServices($parameters, $isApproved);
        $employeeIdList = $this->getRequestEmployeeList($id);
        $servicesIdList = $this->getRequestItemsList($id);
        if (array_key_exists('employees', $parameters)) {
            $employees = $parameters['employees'];
            $this->em->getRepository('CorporateBundle:CorpoRequestServicesEmployee')->deleteRequestServicesEmployee($id);
            foreach ($employees as $employee) {
                $this->em->getRepository('CorporateBundle:CorpoRequestServicesEmployee')->addRequestServicesEmployee($id, $employee);
            }
        } else {
            if ($employeeIdList) {
                $this->em->getRepository('CorporateBundle:CorpoRequestServicesEmployee')->deleteRequestServicesEmployee($id);
            }
        }
        if (array_key_exists('services', $parameters)) {
            $services = $parameters['services'];
            $this->em->getRepository('CorporateBundle:CorpoRequestServicesItems')->deleteRequestServicesItems($id);
            foreach ($services as $service) {
                $this->em->getRepository('CorporateBundle:CorpoRequestServicesItems')->addRequestServicesItems($id, $service);
            }
        } else {
            if ($servicesIdList) {
                $this->em->getRepository('CorporateBundle:CorpoRequestServicesItems')->deleteRequestServicesItems($id);
            }
        }

        return $addResult;
    }
}