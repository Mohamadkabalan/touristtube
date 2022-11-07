<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CorporateBundle\Controller\CorporateController;

/**
 * Controller receiving actions related to all admin
 */
class CorpoAdminController extends CorporateController
{
    public $data = array();

    public function __construct()
    {

    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->containerInitialized();
        global $accountId;
    }

    private function containerInitialized()
    {
//        $this->data['userInfo'] = $this->get('UserServices')->getUserDetails(1);
    }

    /**
     * contoller for the landing page
     *
     * @return TWIG
     */
    public function landingPageAction()
    {
        return $this->render('@Corporate/admin/index.twig', $this->data);
    }

    /**
     * global function to get the employees list
     *
     * @return list
     */
    public function getEmployeesList()
    {
        return $this->get('CorpoAdminServices')->getEmployeeAllList();
    }

    /**
     * global function to get the services list
     *
     * @return list
     */
    public function getServicesList()
    {
        return $this->get('CorpoDefineServicesServices')->getCorpoAdminDefineServicesList();
    }
}