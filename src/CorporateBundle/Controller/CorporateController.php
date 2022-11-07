<?php

namespace CorporateBundle\Controller;

use TTBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CorporateBundle\Model\Menu;

class CorporateController extends DefaultController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        global $request;
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

        if ($this->container->hasParameter('SHOW_FLIGHTS_CORPORATE_BLOCK')) $this->show_flights_corporate_block = $this->container->getParameter('SHOW_FLIGHTS_CORPORATE_BLOCK');
        $this->data['SHOW_FLIGHTS_CORPORATE_BLOCK'] = $this->show_flights_corporate_block;

        $corporate_theme                          = $request->cookies->get('corporate_theme', 1);
        $this->data['corporate_theme']            = $corporate_theme;
        $corporate_background_theme               = $request->cookies->get('corporate_background_theme', 1);
        $this->data['corporate_background_theme'] = $corporate_background_theme;
        $this->data['page_menu_logo']             = '';
        $this->data['page_menu_name']             = '';
        $userObj                                  = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));

        $this->data['profileId'] = $profileId;
        $this->data['affiliateProfileId'] = $this->container->getParameter('AFFILIATES');

        $parameters['accountId']                = $accountId;
        $currentBalance                         = $this->get('CorpoAccountServices')->getCurrentBalance($parameters);
        $this->data['accountCurrentBalance']    = number_format($currentBalance['sumAccountAmount'], 2, '.', ',');
        $this->data['accountPrefferedCurrency'] = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);

        if(isset($menuListArr) && count($menuListArr) > 0)
        {
            $this->data['menulist'] = $menuListArr;
        }else
        {
            $menuObj = Menu::arrayToObject(array());
            if($profileId) {
                $menuObj->profileId = $profileId;
            }

            if($userGroupId == $this->container->getParameter('ROLE_SYSTEM')) {

                $menuObj->rootUser = true;
                $menus = $this->get('CorpoAdminServices')->getMenus($menuObj);
            } else {

                $menuObj->rootUser = false;
                switch($profileId) {
                    case $this->container->getParameter('AFFILIATES'):
                        $profileMenus = $this->container->getParameter('MENU_ACCESS')['AFFILIATES'];
                        break;
                    case $this->container->getParameter('SALES_PERSON'):
                        $profileMenus = $this->container->getParameter('MENU_ACCESS')['SALES_PERSON'];
                        break;
                    case $this->container->getParameter('COMPANY'):
                        $profileMenus = $this->container->getParameter('MENU_ACCESS')['COMPANY'];
                        break;
                    case $this->container->getParameter('AGENCY'):
                        $profileMenus = $this->container->getParameter('MENU_ACCESS')['AGENCY'];
                        break;

                    case $this->container->getParameter('RETAIL_AGENCY'):
                        $profileMenus = $this->container->getParameter('MENU_ACCESS')['RETAIL_AGENCY'];
                        break;
                    case $this->container->getParameter('USERS'):
                        $profileMenus = $this->container->getParameter('MENU_ACCESS')['USERS'];
                        break;
                    default:
                        break;
                }

                $profileMenus = $profileMenus[0] . ', ' . $this->container->getParameter('FIXED_MENUS')[0];
                $profileMenus = explode(", ", $profileMenus);
                $menus = $this->get('CorpoAdminServices')->getMenus($menuObj, $profileMenus);
            }

            $menuList = [];
            /*arrange parent child menu  */
            foreach ($menus as $menu) {
                if(is_null($menu['m_parentId'])) {
                    $menuList[$menu['m_id']][] = $menu;
                } else {
                    $menuList[$menu['m_parentId']][] = $menu;
                }
            }
            //
            $menuListArr            = $this->data['menulist'] = $menuList;
        }

        if(!$menuObj->rootUser) {
            $this->_checkUserMenuPermission($menuListArr);
        }
    }

    /*
     * We just need to set the correct permissions in Menu. 
     * When you type the url manually, sections with unauthorized access are still shown.
     */

    public function _checkUserMenuPermission($menuListArr)
    {
        if(!$menuListArr) return true;

        $currentRoute = $this->getRequest()->getPathInfo();
        $checkMenuPermission = $this->get('CorpoAdminServices')->checkMenuPermission($menuListArr, $currentRoute);

        if(!$checkMenuPermission){
            throw $this->createAccessDeniedException();
        }
    }

    public function corporateListOfUsersAction()
    {
        return $this->render('@Corporate/corporate/corporate-list-of-users.twig', $this->data);
    }

    public function corporateUsersAddEditAction()
    {
        return $this->render('@Corporate/corporate/corporate-users-add-edit.twig', $this->data);
    }

    public function corporateListOfAgenciesAction()
    {
        return $this->render('@Corporate/corporate/corporate-list-of-agencies.twig', $this->data);
    }

    public function corporateAgenciesAddEditAction()
    {
        return $this->render('@Corporate/corporate/corporate-agencies-add-edit.twig', $this->data);
    }

    public function corporateListOfCompaniesAction()
    {
        return $this->render('@Corporate/corporate/corporate-list-of-companies.twig', $this->data);
    }

    public function corporateCompaniesAddEditAction()
    {
        return $this->render('@Corporate/corporate/corporate-companies-add-edit.twig', $this->data);
    }

    public function corporateListOfAffiliatesAction()
    {
        return $this->render('@Corporate/corporate/corporate-list-of-affiliates.twig', $this->data);
    }

    public function corporateAffiliatesAddEditAction()
    {
        return $this->render('@Corporate/corporate/corporate-affiliates-add-edit.twig', $this->data);
    }
    
    public function corporateListOfSalesPersonsAction()
    {
        return $this->render('@Corporate/corporate/corporate-list-of-sales-persons.twig', $this->data);
    }
    
    public function corporateSalesPersonsAddEditAction()
    {
        return $this->render('@Corporate/corporate/corporate-sales-persons-add-edit.twig', $this->data);
    }

    /**
     * Handles corporate authenticate user log in action
     *
     */
    public function corporateRedirectAction()
    {
        return $this->redirectToLangRoute('_corporate', array(), 301);
    }

    public function corporateAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        } else {
            $user             = $this->getUser();
            $userId           = $user->getId();
            $account_id       = $user->getCorpoAccountId();
            $reqSerParams     = array(
                'accountId' => $account_id
            );
            $checkLimitBudget = $this->get('CorpoAccountServices')->isAccountBudgetAllowed($reqSerParams);
            if (!$checkLimitBudget) {
                $this->addErrorNotification($this->translator->trans("Kindly, note that you have reached your budget limit."), 0);
            }
            if (!$this->get('UserServices')->getUserAccountStatus(array('id' => $userId))) {
                throw $this->createAccessDeniedException();
            }
        }

        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        return $this->render('@Corporate/corporate/corporate.twig', $this->data);
    }

    /**
     * This method prepares the pending approval page for all corporate modules
     *
     * @return Renders the pending approval twig
     */
    public function pendingApprovalAction()
    {
        return $this->render('@Corporate/corporate/pending-approval.twig', $this->data);
    }

    /**
     * Corporate Flight landing page
     *
     */
    public function corporateFlightAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'flight_corp';
        return $this->render('@Corporate/corporate/corporate-flight.twig', $this->data);
    }

    /**
     * Corporate Flight Search landing page
     *
     */
    public function corporateFlightSearchAction($srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'flight_corp';
        return $this->render('@Corporate/corporate/corporate-flight-search.twig', $this->data);
    }

    /**
     * Corporate Manage SubAccounts landing page
     *
     */
    public function corporateManageSubAccountsAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/corporate-manage-sub-accounts.twig', $this->data);
    }

    /**
     * Corporate Book Flight Landing Page
     *
     */
    public function corporateBookFlightAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/corporate-book-flight.twig', $this->data);
    }

    /**
     * Corporate Payment Landing Page
     *
     */
    public function corporatePaymentAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/corporate-payment.twig', $this->data);
    }

    /**
     * Corporate Payment Due Landing Page
     *
     */
    public function corporatePaymentDueAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/payment-due.twig', $this->data);
    }

    /**
     * Corporate Travel Approval Landing Page
     *
     */
    public function corporateTravelApprovalsAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/travel-approvals.twig', $this->data);
    }
//    public function corporateTourDetailsAction($seotitle, $seodescription, $seokeywords) {
//        return $this->render('@Corporate/corporate/corporate-tour-details.twig', $this->data);
//    }
//
//    public function corporateDealsBookingDetailsAction($seotitle, $seodescription, $seokeywords) {
//        return $this->render('@Corporate/corporate/corporate-deals-booking-details.twig', $this->data);
//    }
//
//    public function corporateActivitiesAndToursAction($seotitle, $seodescription, $seokeywords) {
//        $this->data['page_menu_logo'] = 'flight_corp';
//        return $this->render('@Corporate/corporate/corporate-activities-and-tours.twig', $this->data);
//    }

    /**
     * Corporate ContactUs Landing Page
     *
     */
    
    public function corporateContactUsAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request = $this->get('request');
        $lang    = $request->getLocale();
        
        return $this->render('@Corporate/static/'.$lang.'_contact.html.twig', $this->data);
    }
    
    public function corporateAboutUsAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request = $this->get('request');
        $lang    = $request->getLocale();
        
        return $this->render('@Corporate/static/'.$lang.'_about_us.html.twig', $this->data);
    }
    
    public function corporateTermsAndConditionsAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        
        return $this->render('@Corporate/static/'.$lang.'_terms-and-conditions.html.twig', $this->data);
    }
    
    public function corporatePrivacyPolicyAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        
        return $this->render('@Corporate/static/'.$lang.'_privacy_policy.html.twig', $this->data);
    }

    /**
     * Corporate notification Page
     *
     */
    public function notificationPageAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/notification-page.twig', $this->data);
    }

    /**
     * Corporate My Trips Landing Page
     *
     */
    public function corporateMyTripsAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/corporate-my-trips.twig', $this->data);
    }

    /**
     * Corporate Flight Details Landing Page
     *
     */
    public function corporateFlightDetailsAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/corporate-flight-details.twig', $this->data);
    }

    public function corporateDepartureFlightAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'flight_corp';
        return $this->render('@Corporate/corporate/corporate-departure-flight.twig', $this->data);
    }

    public function corporateArrivalFlightAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'flight_corp';
        return $this->render('@Corporate/corporate/corporate-arrival-flight.twig', $this->data);
    }

    public function reviewTripAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['page_menu_logo'] = 'flight_corp';
        return $this->render('@Corporate/corporate/corporate-review-trip.twig', $this->data);
    }

    public function myTripDetailedAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/my-trip-detailed.twig', $this->data);
    }

    public function budgetLimitExceededAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->render('@Corporate/corporate/budget-limit-exceeded.twig', $this->data);
    }

    /**
     * global function to get the menu list of all pages
     *
     * @return list
     */
    public function getmenulist($parameters)
    {
        $menu = $this->get('CorpoAdminServices')->getCorpoAdminMenulistPermission($parameters);
        return $menu;
    }

    public function notFoundAction()
    {
        return $this->render('@Corporate/corporate/not-found.twig', $this->data);
    }
}
