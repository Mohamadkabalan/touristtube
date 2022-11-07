<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\libraries\CombogridService;
use CorporateBundle\Model\Menu;

class CorpoAdminServices
{
    protected $utils;
    protected $em;

    public function __construct(Utils $utils, EntityManager $em)
    {
        $this->utils = $utils;
        $this->em    = $em;
    }

    /**
     * getting from the repository all menu permissions
     *
     * @return list
     */
    public function getCorpoAdminMenulistPermission($parameters)
    {
        $menu = $this->em->getRepository('CorporateBundle:CorpoAdminMenu')->getCorpoAdminMenu($parameters);
        return $menu;
    }

    /**
     * getting from the repository all menus
     *
     * @param $menuObj
     * @return list
     */
    public function getMenus($menuObj, $profileMenus = NULL)
    {
        $menu = $this->em->getRepository('CorporateBundle:CorpoAdminMenu')->getMenus($menuObj, $profileMenus);
        return $menu;
    }

    /**
     * Get menu items by roleId
     *
     * @param $roleId
     */
    public function getMenuItemsByRole($roleId)
    {
        $menu = $this->em->getRepository('CorporateBundle:CorpoAdminMenuRoles')->getMenuItemsByRole($roleId);
        return $menu;
    }

    /**
     * getting menu info by url
     *
     * @param $url
     * @return menuObj
     */
    public function getMenusInfoByURL($url = '')
    {
        if ($url){
            return $this->em->getRepository('CorporateBundle:CorpoAdminMenu')->findBy(array('url' => $url));
        }
        return false;
    }

    /*
     * We just need to set the correct permissions in Menu. 
     * When you type the url manually, sections with unauthorized access are still shown.
     */

    public function checkMenuPermission($menuListArr, $currentRoute){

        // if current URL is not in mune, then just return true. No need to check
        $menusInfoByURL = $this->getMenusInfoByURL($currentRoute);
        if(!$menusInfoByURL || !$currentRoute) return true;

        $url = $menusInfoByURL[0]->getUrl();
        $return = false;
        foreach($menuListArr as $menuList){
            if(count($menuList) > 1){
                foreach($menuList as $menu){
                    if($url == $menu['m_url']){
                        $return = true;
                    }
                }
            }else{
                if($url == $menuList[0]['m_url']){
                    $return = true;
                }
            }
        }

        return $return;
    }
    /**
     * getting menu info by slug from url
     *
     * @param $slug
     * @return menuObj
     */
    public function getMenusPathBySlug($slug = '')
    {
        if ($slug){
            $menu =  $this->em->getRepository('CorporateBundle:CorpoAdminMenu')->getMenusPathBySlug($slug);
            if($menu){
                $params = array();
                $params['id'] = $menu['m_id'];
                $params['name'] = $menu['m_name'];
                return Menu::arrayToObject($params);                
            }
            return false;
        }
        return false;
    }

    /**
     * getting from the repository all employees
     *
     * @return list
     */
    public function getEmployeeAllList()
    {
        $sessionInfo    = $this->getLoggedInSessionInfo();
        $accountId      = $sessionInfo['accountId'];
        $departmentList = $this->em->getRepository('CorporateBundle:CorpoEmployees')->getEmployeeAllList($accountId);
        return $departmentList;
    }

    /**
     * getting from the account user menu list
     *
     * @return list
     */
    public function getAccountUserMenuList($id)
    {
        $MenuList = $this->em->getRepository('CorporateBundle:CorpoAccountPermission')->getAccountUserMenuList($id);
        return $MenuList;
    }

    /**
     * getting from the account menu persmission list
     *
     * @return list
     */
    public function getAccountMenuByIdList($id)
    {
        $MenuList = $this->em->getRepository('CorporateBundle:CorpoAccountPermission')->getAccountMenuByIdList($id);
        return $MenuList;
    }

    /**
     * getting list of cities like term
     *
     * @return list
     */
    public function getListLikeCities($term, $includeId, $limit)
    {
        $citiesList = $this->em->getRepository('CorporateBundle:Webgeocities')->getListLikeCities($term, $includeId, $limit);

        return $citiesList;
    }

    /**
     * getting cityinfo by id
     *
     * @return cityinfo
     */
    public function getCityInfo($cityId)
    {
        if(!$cityId) return false; 

        return $this->em->getRepository('CorporateBundle:Webgeocities')->getCityInfo($cityId);
    }

    /**
     * getting from the repository all accounts
     *
     * @return list
     */
    public function getCorpoAdminLikeAccounts($term, $limit)
    {
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccount')->getCorpoAdminLikeAccounts($term, $limit);
        return $accountList;
    }

    /**
     * getting from the repository all countries
     *
     * @return list
     */
    public function getCountryCombo($term, $limit)
    {
        $country = $this->em->getRepository('CorporateBundle:CmsCountries')->getCountryCombo($term, $limit);
        return $country;
    }

    /**
     * getting from the repository all users
     *
     * @return list
     */
    public function getCorpoAdminLikeUsers($term, $limit, $id, $accountIdRQ = 0)
    {
        if ($accountIdRQ == 0) {
            $logInfo = $this->getLoggedInSessionInfo();
            if ($logInfo['accountId'] != NULL || $logInfo['accountId'] != '') {
                $accountId = $logInfo['accountId'];
            } else {

                return false;
            }
        } else {
            $accountId = $accountIdRQ;
        }


//        $logInfo=$this->getLoggedInSessionInfo();
//        $accountId = $logInfo['accountId'];
        $usersList = $this->em->getRepository('CorporateBundle:CmsUsers')->getCorpoAdminLikeUsers($term, $limit, $id, $accountId);
        return $usersList;
    }

    /**
     * getting from the repository all currencies
     *
     * @return list
     */
    public function getCorpoAdminLikeCurrency($term, $limit)
    {
        $currency = $this->em->getRepository('CorporateBundle:Currency')->getCorpoAdminLikeCurrency($term, $limit);
        return $currency;
    }

    /**
     * getting from the repository all currencies
     *
     * @return list
     */
    public function getAccountPaymentList()
    {
        $currency = $this->em->getRepository('CorporateBundle:CorpoPaymentType')->getAccountPaymentList();
        return $currency;
    }

    /**
     * getting from the repository the pending request details
     *
     * @return list
     */
    public function getPendingRequestDetailsId($parameters)
    {
        return $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->getPendingRequestDetailsId($parameters['reservationId'], $parameters['moduleId']);
    }

    /**
     * getting logged in user and account info
     *
     * @return list
     */
    public function getLoggedInSessionInfo()
    {
        global $accountId;
        global $userId;
        global $userGroupId;
        global $profileId;
        global $profileName;
        global $profileLevel;
        global $menuListArr;
        global $allowAccessSubAccounts;
        global $allowAccessSubAccountsUsers;
        global $allowApproval;

        $sessionInfo                 = array();
        $sessionInfo['userId']       = $userId;
        $sessionInfo['accountId']    = $accountId;
        $sessionInfo['userGroupId']  = $userGroupId;
        $sessionInfo['profileId']    = $profileId;
        $sessionInfo['profileName']  = $profileName;
        $sessionInfo['profileLevel'] = $profileLevel;
        $sessionInfo['menuList']     = $menuListArr;
        $sessionInfo['allowAccessSubAccounts'] = $allowAccessSubAccounts;
        $sessionInfo['allowAccessSubAccountsUsers'] = $allowAccessSubAccountsUsers;
        $sessionInfo['allowApproval'] = $allowApproval;
        return $sessionInfo;

    }

    public function getCityCombo(Request $request, $countryCode)
    {
        if($countryCode == '') {
            $countryCode = $request->request->get("countryCode");
        }

        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        $tt_search_critiria_obj->addParam('countryCode', $countryCode);
        //
        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:Webgeocities')->getCityCombo($tt_search_critiria_obj);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }

    public function getCurrencyCombo(Request $request)
    {
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        //
        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:Currency')->getCurrencyCombo($tt_search_critiria_obj);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }
}
