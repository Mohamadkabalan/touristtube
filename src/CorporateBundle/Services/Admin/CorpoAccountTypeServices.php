<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\libraries\CombogridService;

class CorpoAccountTypeServices
{
    protected $utils;
    protected $em;
    protected $container;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container)
    {
        $this->utils              = $utils;
        $this->em                 = $em;
        $this->container          = $container;
    }

    /**
     * getting from the repository all account types
     *
     * @return list
     */
    public function getCorpoAdminAccountTypeList()
    {
        $accountTypeList = $this->em->getRepository('CorporateBundle:CorpoAccountType')->getAccountTypeList();
        return $accountTypeList;
    }

    public function prepareAccountTypeDtQuery()
    {
        return $this->em->getRepository('CorporateBundle:CorpoAccountType')->prepareAccountTypeDtQuery();
    }

    /**
     * getting from the repository all active account types
     *
     * @return list
     */
    public function getCorpoAdminAccountTypeActiveList()
    {
        $accountTypeList = $this->em->getRepository('CorporateBundle:CorpoAccountType')->getAccountTypeActiveList();
        return $accountTypeList;
    }

    /**
     * getting from the repository an account type by id
     *
     * @return list
     */
    public function getCorpoAdminAccountTypeById($id)
    {
        $accountType = $this->em->getRepository('CorporateBundle:CorpoAccountType')->getAccountTypeById($id);
        return $accountType;
    }

    /**
     * getting from the repository an account type accessible menus by id
     *
     * @return list
     */
    public function getAccountTypeMenuListById($id)
    {
        $menus = $this->em->getRepository('CorporateBundle:CorpoAccountType')->getAccountTypeMenusById($id);
        return $menus;
    }

    /**
     * adding an account type
     *
     * @return list
     */
    public function addAccountType($acctTypeObj)
    {
        $accountTypeId = $this->em->getRepository('CorporateBundle:CorpoAccountType')->addAccountType($acctTypeObj);
        return $accountTypeId;
    }

    /**
     * adding an account type menu access
     *
     * @return list
     */
    public function addAccountTypeMenus($menuObj)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoAccountTypeMenu')->addAccountTypeMenus($menuObj);
        return $result;
    }

    /**
     * updating an account type
     *
     * @return list
     */
    public function updateAccountType($acctTypeObj)
    {
        $accountType = $this->em->getRepository('CorporateBundle:CorpoAccountType')->updateAccountType($acctTypeObj);
        $this->em->getRepository('CorporateBundle:CorpoAccountTypeMenu')->deleteAccountTypeMenuList($acctTypeObj->getId());
        return $accountType;
    }

    /**
     * deleting an account type
     *
     * @return list
     */
    public function deleteCorpoAccountType($id)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoAccountType')->deleteAccountType($id);
        return $result;
    }

    /**
     * activating an account type
     *
     * @return list
     */
    public function activateCorpoAccountType($id)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoAccountType')->activateAccountType($id);
        return $result;
    }

    /**
     * deaactivating an account type
     *
     * @return list
     */
    public function deactivateCorpoAccountType($id)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoAccountType')->deactivateAccountType($id);
        return $result;
    }

    /*
     *
     * This method checks for duplicate entry account type name
     * Can be used both in modify and adding account type
     *
     * @param $params
     *
     * @return boolean true or false
     */

    public function checkDuplicateAccountType($params)
    {
        return $this->em->getRepository('CorporateBundle:CorpoAccountType')->checkDuplicateAccountType($params);
    }

    /**
     * getting from the repository all active account types
     *
     * @return list
     */
    public function getAccountTypeCombo(Request $request)
    {
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        //
        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:CorpoAccountType')->getAccountTypeCombo($tt_search_critiria_obj);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }
}