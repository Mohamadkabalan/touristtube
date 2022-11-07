<?php

namespace CorporateBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReportsCorpoController extends CorporateController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
    }

    public function reportsAllBookingsAction()
    {
        $commonCode                             = $this->commonCode();
        $countItem                              = $commonCode['countItem'];
        $pg_start_page                          = 0;
        $page                                   = 1;
        $pg_limit_records                       = $commonCode['pg_limit_records'];
        $count                                  = $commonCode['count'];
        $class                                  = $commonCode['class'];
        $pagination                             = '';
        $modules                                = $commonCode['modules'];
        $accountId                              = $commonCode['accountId'];
        $userId                                 = $commonCode['userId'];
        $parameters['accountId']                = $accountId;
        $parameters['userId']                   = $userId;
        $statuses                               = $commonCode['statuses'];
        $this->data['preferredAccountCurrency'] = $commonCode['preferredAccountCurrency'];
        $this->data['hotelModuleId']            = $commonCode['hotelModuleId'];
        $this->data['flightModuleId']           = $commonCode['flightModuleId'];
        $this->data['dealModuleId']             = $commonCode['dealModuleId'];
        $this->data['approvedStatus']           = $commonCode['approvedStatus'];
        $this->data['accountId']                = $commonCode['accountId'];
        $this->data['modules']                  = $modules;
        $params                                 = array(
            'accountId' => $accountId,
            'userId' => $userId,
            'statuses' => $statuses,
            'start' => $pg_start_page,
            'limit' => $pg_limit_records
        );
        $userObj                                = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        if ($userObj[0]['cu_cmsUserGroupId'] == $this->container->getParameter('ROLE_SYSTEM')) {
            $this->data['role'] = $this->container->getParameter('ROLE_SYSTEM');
        }
        $allApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        $params['sumAmount'] = 1;
        $params['statuses'] = array(2);
        $sumApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        $totalAmt = $sumApprovalFlowList[0]['totalAmt'];
        unset($params['sumAmount']);
        $params['statuses'] = $statuses;

        $params['count']       = 1;
        $countApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if ($countApprovalFlowList) {
            $countItem  = $countApprovalFlowList;
            $pagination = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page, $count, $class);
        }
        $this->data['pagination']          = $pagination;
        $this->data['allApprovalFlowList'] = $allApprovalFlowList;
        $this->data['totalAmt'] = $totalAmt;
        return $this->render('@Corporate/admin/reports/reports-allbookings.twig', $this->data);
    }

    /**
     * controller action for filtering my bookings
     *
     * @return json rendering a twig
     */
    public function filterMyBookingsAction()
    {
        $params = $this->get('request')->request->all();

        $request       = Request::createFromGlobals();
        $data          = array();
        $data2         = array();
        $fromDate      = $request->request->get('fromDay', '');
        $toDate        = $request->request->get('toDay', '');
        $accountId     = $request->request->get('accountId', '');
        $userId        = $request->request->get('userId', '');
        $currencyCode  = $request->request->get('currencyCode', '');
        $types         = $request->request->get('types', '');
        $pg_start_page = $request->request->get('start', '');
        $accountTypeId = $request->request->get('accountTypeId', '');

        if (!$pg_start_page) $pg_start_page = 1;

        $commonCode = $this->commonCode();
        $countItem  = $commonCode['countItem'];

        if ($fromDate) {
            $fromDate = new \DateTime($fromDate);
            $fromDate = $fromDate->format('Y-m-d');
        }
        if ($toDate) {
            $toDate = new \DateTime($toDate);
            $toDate = $toDate->format('Y-m-d');
        }
        $class            = $commonCode['class'];
        $pg_limit_records = $commonCode['pg_limit_records'];
        $page             = $pg_start_page;
        $count            = $commonCode['count'];
        $pagination       = '';
        $pg_start_page    = ( $pg_start_page * $pg_limit_records ) - $pg_limit_records;
        $modules          = $commonCode['modules'];

        if (!$accountId) {
            $accountId = $commonCode['accountId'];
        }

        if (!$userId) {
            $userId = $commonCode['userId'];
        }
        $parameters['accountId']           = $accountId;
        $parameters['userId']              = $userId;
        $status                            = $commonCode['statuses'];
        $data2['preferredAccountCurrency'] = $commonCode['preferredAccountCurrency'];
        $data2['hotelModuleId']            = $commonCode['hotelModuleId'];
        $data2['flightModuleId']           = $commonCode['flightModuleId'];
        $data2['dealModuleId']             = $commonCode['dealModuleId'];
        $data2['approvedStatus']           = $commonCode['approvedStatus'];
        $data2['accountId']                = $accountId;
        $data2['modules']                  = $modules;

        //this is for generateLink will work
        // $data2['hotels_link']  = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/corporate/hotels/booking-details-', 'corporate');
        // $data2['flights_link'] = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/corporate/flight/details?', 'corporate');
        // $data2['deals_link']   = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/corporate/deals/deals-booking-details-', 'corporate');

        $params = array(
            'accountId' => $accountId,
            'userId' => $userId,
            'currencyCode' => $currencyCode,
            'createdFrom' => $fromDate,
            'createdTo' => $toDate,
            'types' => $types,
            'statuses' => $status,
            'start' => $pg_start_page,
            'limit' => $pg_limit_records,
            'typeOfAccount' => $accountTypeId,
        );

        $approvalFlow = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        $params['sumAmount'] = 1;
        $params['statuses'] = array(2);
        $sumApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        $totalAmt = $sumApprovalFlowList[0]['totalAmt'];
        unset($params['sumAmount']);
        $params['statuses'] = $status;

        $params['count']       = 1;
        $countApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if ($countApprovalFlowList) {
            $countItem  = $countApprovalFlowList;
            $pagination = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page, $count, $class);
        }

        $data2['allApprovalFlowList'] = $approvalFlow;
        $data['allApprovalFlow']      = $this->render('@Corporate/admin/reports/reports-all-bookings-info.twig', $data2)->getContent();
        $data['totalAmt'] = $totalAmt;

        if ($pagination) {
            $data['pagination'] = $pagination;
        }

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * function that returns an array full of variable needed in my booking page
     *
     * @return array
     */
    private function commonCode()
    {
        $commonArray = array();

        $sessionInfo      = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId        = $sessionInfo['accountId'];
        $userId           = $sessionInfo['userId'];
        $pg_limit_records = $this->container->getParameter('TRAVEL_APPROVAL_NUMBER_OF_RECORDS');
        $count            = $this->container->getParameter('TRAVEL_APPROVAL_PAGINATION_COUNT');
        $class            = "approval_pagination";
        $countItem        = 0;

        $modules = array(
            [
                'name' => $this->translator->trans('All Bookings'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ]
        );
        $modules[] = ['name' => $this->translator->trans('Flights'), 'id' => $this->container->getParameter('MODULE_FLIGHTS')];
        $modules[] = ['name' => $this->translator->trans('Hotels'), 'id' => $this->container->getParameter('MODULE_HOTELS')];
        if ($this->show_deals_block == 1) {
            $modules[] = ['name' => $this->translator->trans('Deals'), 'id' => $this->container->getParameter('MODULE_DEALS')];
        }
        $statuses                 = array($this->container->getParameter('CORPO_APPROVAL_APPROVED'), $this->container->getParameter('CORPO_APPROVAL_CANCELED'));
        $preferredAccountCurrency = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $hotelModuleId            = $this->container->getParameter('MODULE_HOTELS');
        $flightModuleId           = $this->container->getParameter('MODULE_FLIGHTS');
        $dealModuleId             = $this->container->getParameter('MODULE_DEALS');
        $approvedStatus           = $this->container->getParameter('CORPO_APPROVAL_APPROVED');

        $commonArray['accountId']                = $accountId;
        $commonArray['userId']                   = $userId;
        $commonArray['pg_limit_records']         = $pg_limit_records;
        $commonArray['count']                    = $count;
        $commonArray['class']                    = $class;
        $commonArray['countItem']                = $countItem;
        $commonArray['modules']                  = $modules;
        $commonArray['statuses']                 = $statuses;
        $commonArray['hotelModuleId']            = $hotelModuleId;
        $commonArray['flightModuleId']           = $flightModuleId;
        $commonArray['dealModuleId']             = $dealModuleId;
        $commonArray['approvedStatus']           = $approvedStatus;
        $commonArray['preferredAccountCurrency'] = $preferredAccountCurrency;

        return $commonArray;
    }

    public function reportsPaymentsAction()
    {
        $request   = Request::createFromGlobals();
        $firstDay  = $request->query->get('fDate', '');
        $lastDay   = $request->query->get('tDate', '');
        $accountId = $request->query->get('accountId', '');

        if (empty($accountId)) {
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId   = $sessionInfo['accountId'];
        }
        if (!isset($firstDay)) {
            $fDate    = new DateTime('first day of this month');
            $firstDay = $fDate->format('Y-m-d');
        }
        if (!isset($lastDay)) {
            $tDate   = new \DateTime("now");
            $lastDay = $tDate->format('Y-m-d');
        }

        $parameters['accountId'] = $accountId;
        $parameters['firstDay']  = $firstDay;
        $parameters['lastDay']   = $lastDay;
        
        $this->data['accountId'] = $accountId;
        $this->data['firstDay']  = $firstDay;
        $this->data['lastDay']   = $lastDay;
        $this->data['amountFBC'] = $this->container->getParameter('FBC_CODE');
        $this->data['amountSBC'] = $this->container->getParameter('SBC_CODE');

        $this->data['accountPrefferedCurrency'] = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);

        $listSum                    = $this->get('CorpoAccountPaymentServices')->getAccountPaymentTotals($parameters);
        $this->data['sumFBC']       = $listSum['sumAmountFBC'];
        $this->data['sumSBC']       = $listSum['sumAmountSBC'];
        $this->data['sumPreferred'] = $listSum['sumAccountAmount'];

        $accountPaymentList               = $this->get('CorpoAccountPaymentServices')->getCorpoAdminAccountPaymentList($parameters);

        $firstDay = new \DateTime('-1 month');
        $firstDay = $firstDay->format('Y-m-d');
        $parameters['firstDay'] = $firstDay;
        $currentBalance                         = $this->get('CorpoAccountServices')->getCurrentBalance($parameters);
        $this->data['accountDueBalance']        = $currentBalance['sumAccountAmount'];

        $this->data['accountPaymentList'] = $accountPaymentList;
        $currDate = date('Y-m-d');
        $monthAgo = date('Y-m-d', strtotime($currDate . '-1 month'));
        $this->data['monthAgoDate'] = $monthAgo;

        return $this->render('@Corporate/admin/reports/reports-payments.twig', $this->data);
    }

    public function ReportsSalesAction()
    {
        $request         = Request::createFromGlobals();
        $searchUserId    = $request->query->get('userId', '');
        $searchAccountId = $request->query->get('accountId', '');
        $firstDay        = $request->query->get('fDate', '');
        $lastDay         = $request->query->get('tDate', '');
        $currency        = $request->query->get('currency', '');
        $accType         = $request->query->get('type', '');

        $userObj   = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $accountId = $userObj[0]['cu_corpoAccountId'];

        if (!isset($firstDay) || empty($firstDay)) {
            $toDay    = new \DateTime("-1 month");
            $firstDay = $toDay->format('Y-m-d');
        }
        if (!isset($lastDay) || empty($lastDay)) {
            $todayDate = new \DateTime("now");
            $lastDay   = $todayDate->format('Y-m-d');
        }
        if (isset($currency) && !empty($lastDay)) {
            $this->data['currencyCode'] = $currency;
        }
        if (isset($accType) && $accType != 0) {
            $this->data['acctType'] = $accType;
        }

        $modules = array(
            [
                'name' => $this->translator->trans('All'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ]
        );
        $modules[] = ['name' => $this->translator->trans('Flights'), 'id' => $this->container->getParameter('MODULE_FLIGHTS')];
        $modules[] = ['name' => $this->translator->trans('Hotels'), 'id' => $this->container->getParameter('MODULE_HOTELS')];
        if ($this->show_deals_block == 1) {
            $modules[] = ['name' => $this->translator->trans('Deals'), 'id' => $this->container->getParameter('MODULE_DEALS')];
        }

        $parameters['firstDay'] = $firstDay;
        $parameters['lastDay']  = $lastDay;
        if ($searchAccountId) {
            $parameters['accountId'] = $searchAccountId;
        } else {
            $parameters['accountId'] = $accountId;
        }
        if ($searchUserId) {
            $parameters['userId'] = $searchUserId;
        }

        $this->data['firstDay']                = $firstDay;
        $this->data['lastDay']                 = $lastDay;
        $this->data['amountFBC']               = $this->container->getParameter('FBC_CODE');
        $this->data['amountSBC']               = $this->container->getParameter('SBC_CODE');
        $this->data['preferredCurrency']       = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $listSum                               = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionTotals($parameters);
        $this->data['sumFBC']                  = number_format($listSum['sumAmountFBC'], 2, '.', ',');
        $this->data['sumSBC']                  = number_format($listSum['sumAmountSBC'], 2, '.', ',');
        $this->data['sumPreferred']            = number_format($listSum['sumAccountAmount'], 2, '.', ',');
        $this->data['accountTransactionsList'] = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountTransactionsList($parameters);
        if ($userObj[0]['cu_cmsUserGroupId'] == $this->container->getParameter('ROLE_SYSTEM')) {
            $this->data['role'] = $this->container->getParameter('ROLE_SYSTEM');
            if (!$searchUserId) {
                $accountInfo                   = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
                $accountName                   = $accountInfo['al_name'];
                $this->data['accountName']     = $accountName;
                $this->data['searchAccountId'] = $accountId;
            }
        }
        $this->data['modules'] = $modules;
        if ($searchAccountId) {
            $accountInfo                   = $this->get('CorpoAccountServices')->getCorpoAdminAccount($searchAccountId);
            $accountName                   = $accountInfo['al_name'];
            $this->data['accountName']     = $accountName;
            $this->data['searchAccountId'] = $searchAccountId;
        }
        if ($searchUserId) {
            $userInfo                     = $this->get('UserServices')->getUserDetails(array('id' => $searchUserId));
            $userName                     = $userInfo[0]['cu_fullname'];
            $this->data['searchUserName'] = $userName;
            $this->data['searchUserId']   = $searchUserId;
        }

        return $this->render('@Corporate/admin/reports/reports-sales.twig', $this->data);
    }

    public function ReportsCustomersAction()
    {
        $request         = Request::createFromGlobals();
        $searchUserId    = $request->query->get('userId', '');
        $searchAccountId = $request->query->get('accountId', '');
        $currency        = $request->query->get('currency', '');
        $accType         = $request->query->get('type', '');

        $userObj   = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $accountId = $userObj[0]['cu_corpoAccountId'];


        $modules = array(
            [
                'name' => $this->translator->trans('All'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ]
        );
        $modules[] = ['name' => $this->translator->trans('Flights'), 'id' => $this->container->getParameter('MODULE_FLIGHTS')];
        $modules[] = ['name' => $this->translator->trans('Hotels'), 'id' => $this->container->getParameter('MODULE_HOTELS')];
        if ($this->show_deals_block == 1) {
            $modules[] = ['name' => $this->translator->trans('Deals'), 'id' => $this->container->getParameter('MODULE_DEALS')];
        }

        if ($searchAccountId) {
            $parameters['accountId'] = $searchAccountId;
        } else {
            $parameters['accountId'] = $accountId;
        }
        if ($searchUserId) {
            $parameters['userId'] = $searchUserId;
        }

        $this->data['hideDateFilter']          = 1;
        $this->data['amountFBC']               = $this->container->getParameter('FBC_CODE');
        $this->data['amountSBC']               = $this->container->getParameter('SBC_CODE');
        $this->data['preferredCurrency']       = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $listSum                               = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionTotals($parameters);
        $this->data['sumFBC']                  = number_format($listSum['sumAmountFBC'], 2, '.', ',');
        $this->data['sumSBC']                  = number_format($listSum['sumAmountSBC'], 2, '.', ',');
        $this->data['sumPreferred']            = number_format($listSum['sumAccountAmount'], 2, '.', ',');
        $this->data['accountTransactionsList'] = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountTransactionsList($parameters);
        if ($userObj[0]['cu_cmsUserGroupId'] == $this->container->getParameter('ROLE_SYSTEM')) {
            $this->data['role'] = $this->container->getParameter('ROLE_SYSTEM');
            if (!$searchUserId) {
                $accountInfo                   = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
                $accountName                   = $accountInfo['al_name'];
                $this->data['accountName']     = $accountName;
                $this->data['searchAccountId'] = $accountId;
            }
        }
        $this->data['modules'] = $modules;
        if ($searchAccountId) {
            $accountInfo                   = $this->get('CorpoAccountServices')->getCorpoAdminAccount($searchAccountId);
            $accountName                   = $accountInfo['al_name'];
            $this->data['accountName']     = $accountName;
            $this->data['searchAccountId'] = $searchAccountId;
        }
        if ($searchUserId) {
            $userInfo                     = $this->get('UserServices')->getUserDetails(array('id' => $searchUserId));
            $userName                     = $userInfo[0]['cu_fullname'];
            $this->data['searchUserName'] = $userName;
            $this->data['searchUserId']   = $searchUserId;
        }

        return $this->render('@Corporate/admin/reports/reports-customers.twig', $this->data);
    }

    public function getCustomerDataTableAction()
    {
        $dtService = $this->get('CorpoDatatable');
        $request   = $this->get('request')->request->all();

        $sql = $this->get('CorpoUsersServices')->prepareCustomerTransactionsDtQuery($request);

        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => $sql->where,
            'table' => 'cms_uers',
            'key' => 'cu.id',
            'columns' => NULL,
        );

        $queryArr     = $dtService->buildQuery($params);
        $transactions = $dtService->runQuery($queryArr);

        return new JsonResponse($transactions);
    }
}