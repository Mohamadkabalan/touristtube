<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller receiving actions related to manage the bookings
 */
class CorpoManageBookingsController extends CorpoAdminController
{
    /**
     * This method returns common variable needed to manage booking page
     *
     * @return array|mixed
     */
    private function commonCode(){

        $params = array();
        
        $sessionInfo  = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        $userId = $sessionInfo['userId'];
        $pg_limit_records = $this->container->getParameter('TRAVEL_APPROVAL_NUMBER_OF_RECORDS');
        $count = $this->container->getParameter('TRAVEL_APPROVAL_PAGINATION_COUNT');
        $class="approval_pagination";
        $countItem    = 0;
        
        $modules    = array(
            [
                'name' => $this->translator->trans('All Bookings'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ]          
        );
        $modules[] = ['name' => $this->translator->trans('Flights'),'id' => $this->container->getParameter('MODULE_FLIGHTS')];
        $modules[] = ['name' => $this->translator->trans('Hotels'),'id' => $this->container->getParameter('MODULE_HOTELS')];
        if ( $this->show_deals_block == 1 ) {
            $modules[] = ['name' => $this->translator->trans('Deals'),'id' => $this->container->getParameter('MODULE_DEALS')];
        }
        $status  = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
        $preferredAccountCurrency = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $hotelModuleId  = $this->container->getParameter('MODULE_HOTELS');
        $flightModuleId = $this->container->getParameter('MODULE_FLIGHTS');
        $dealModuleId  = $this->container->getParameter('MODULE_DEALS');
        $approvedStatus = $this->container->getParameter('CORPO_APPROVAL_APPROVED');

        $params['accountId'] = $accountId;
        $params['userId'] = $userId;
        $params['pg_limit_records'] = $pg_limit_records;
        $params['count'] = $count;
        $params['class'] = $class;
        $params['countItem'] = $countItem;
        $params['modules'] = $modules;
        $params['status'] = $status;
        $params['hotelModuleId'] = $hotelModuleId;
        $params['flightModuleId'] = $flightModuleId;
        $params['dealModuleId'] = $dealModuleId;
        $params['approvedStatus'] = $approvedStatus;
        $params['preferredAccountCurrency'] = $preferredAccountCurrency;

        return $params;
    }

    /**
     * Update Expired Records
     */
    protected function updateExpiry($accountId){
        $parameter = array(
            'accountId' => $accountId,
            'FLIGHT_EXPIRY_TIME' => $this->container->getParameter('FLIGHT_EXPIRY_TIME'),
            'HOTEL_EXPIRY_TIME' => $this->container->getParameter('HOTEL_EXPIRY_TIME'),
            'DEAL_EXPIRY_TIME' => $this->container->getParameter('DEAL_EXPIRY_TIME'),
            'CORPO_APPROVAL_EXPIRED' => $this->container->getParameter('CORPO_APPROVAL_EXPIRED'),
            'CORPO_APPROVAL_APPROVED' => $this->container->getParameter('CORPO_APPROVAL_APPROVED'),
            'CORPO_APPROVAL_PENDING' => $this->container->getParameter('CORPO_APPROVAL_PENDING'),
            'MODULE_FINANCE' => $this->container->getParameter('MODULE_FINANCE'),
            'MODULE_FLIGHTS' => $this->container->getParameter('MODULE_FLIGHTS'),
            'MODULE_HOTELS' => $this->container->getParameter('MODULE_HOTELS'),
            'MODULE_DEALS' => $this->container->getParameter('MODULE_DEALS')
        );
        $this->get('CorpoApprovalFlowServices')->updateExpiredRecords($parameter);
    }

    /**
     * Action to manage Bookings
     *
     * @return Twig
     */
    public function manageBookingsAction() {
        $request = $this->getRequest();

        $commonCode = $this->commonCode();
        $countItem = $commonCode['countItem'];
        $pg_start_page = 0;
        $page = 1;
        $pg_limit_records = $commonCode['pg_limit_records'];
        $count = $commonCode['count'];
        $class=$commonCode['class'];
        $pagination= '';
        $modules = $commonCode['modules'];
        $accountId = $commonCode['accountId'];
        $userId = $commonCode['userId'];
        $parameters['accountId'] = $accountId;
        $parameters['userId'] = $userId;
        $status = $commonCode['status'];
        $this->data['preferredAccountCurrency'] = $commonCode['preferredAccountCurrency'];
        $this->data['hotelModuleId'] = $commonCode['hotelModuleId'];
        $this->data['flightModuleId'] = $commonCode['flightModuleId'];
        $this->data['dealModuleId'] = $commonCode['dealModuleId'];
        $this->data['approvedStatus'] = $commonCode['approvedStatus'];
        $this->data['accountId'] = $commonCode['accountId'];
        $this->data['modules'] = $modules;
        $this->data['expiredStatus']  = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
        $this->data['canceledStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
        $this->data['pendingStatus'] = $this->container->getParameter('CORPO_APPROVAL_PENDING');
        $this->data['rejectedStatus'] = $this->container->getParameter('CORPO_APPROVAL_REJECTED');
        $this->data['FLIGHT_LAST_HOUR_TO_CANCEL'] = $this->container->getParameter('FLIGHT_LAST_HOUR_TO_CANCEL');

        $params = array(
            'accountId' => $accountId,
            'start'  => $pg_start_page,
            'limit'  => $pg_limit_records
        );
        
        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        if($userObj[0]['cu_cmsUserGroupId'] == $this->container->getParameter('ROLE_SYSTEM')){
            $this->data['role'] = $this->container->getParameter('ROLE_SYSTEM');
            $this->data['allowApprove'] = 1;
        } else {
            $this->data['searchUserName'] = $userObj[0]['cu_fullname'];
            $account = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
            $this->data['accountName'] = $account['al_name'];
            $this->data['disableAccFilter'] = true;
            $this->data['allowApprove'] = $userObj[0]['cu_allowApproval'];
            $this->data['disableUserFilter'] = false;
            if(!$userObj[0]['cu_allowApproval']) {
                $this->data['disableUserFilter'] = true;
                $params['userId'] = $userId;
            }
        }

        $this->updateExpiry($accountId);
        $this->data['status'] = $this->data['pendingStatus'];
        $params['statuses'] = array($this->data['status']);
        $this->data['types'] = [];

        $totalSum = $this->get('CorpoApprovalFlowServices')->getRequestDetailSumOfAmount($accountId, $this->data['status'], $params);
        $this->data['sumPreferredAccountAmount'] = $totalSum['sumAccountAmount'];

        $allApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        $params['count']= 1;

        if(!empty($allApprovalFlowList)){
            $countItem = count($allApprovalFlowList);
            $pagination  = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page,$count ,$class );
        }

        $this->data['pagination'] = $pagination;
        $this->data['allApprovalFlowList'] = $allApprovalFlowList;

        return $this->render('@Corporate/admin/manageBookings/corporate-manage-bookings.twig', $this->data);

    }

    /**
     * Controller filter action
     *
     * @return json rendering a twig
     */
    public function filterAction()
    {
        $request = $this->getRequest()->request->all();

        $commonCode     = $this->commonCode();
        $pagination  = '';

        $fromDate = isset($request['fromDate']) ? $request['fromDate'] :'';
        $toDate = isset($request['toDate']) ? $request['toDate'] : '';
        $types = isset($request['serviceType']) ? explode(",", $request['serviceType']) : array(0);
        $status = isset($request['bookingStatus']) ? $request['bookingStatus'] : 0;
        $page = isset($request['page']) ? $request['page'] : 1;

        $class = $commonCode['class'];
        $pageLimit = $commonCode['pg_limit_records'];
        $count = $commonCode['count'];
        $modules = $commonCode['modules'];

        $accountId = isset($request['accountId']) ? $request['accountId'] : $commonCode['accountId'];
        $userId = isset($request['userId']) ? $request['userId'] : $commonCode['userId'];

        $pg_start_page  = ( $page * $pageLimit ) - $pageLimit;

        //$templateData = array();
        $this->data['expiredStatus']  = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
        $this->data['canceledStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
        $this->data['rejectedStatus'] = $this->container->getParameter('CORPO_APPROVAL_REJECTED');
        $this->data['FLIGHT_LAST_HOUR_TO_CANCEL'] = $this->container->getParameter('FLIGHT_LAST_HOUR_TO_CANCEL');
        $this->data['preferredAccountCurrency'] = $commonCode['preferredAccountCurrency'];
        $this->data['hotelModuleId'] = $commonCode['hotelModuleId'];
        $this->data['flightModuleId'] = $commonCode['flightModuleId'];
        $this->data['dealModuleId'] = $commonCode['dealModuleId'];
        $this->data['approvedStatus'] = $commonCode['approvedStatus'];
        $this->data['accountId'] = $accountId;
        $this->data['modules'] = $modules;

        $params = array(
            'accountId' => $accountId,
            'userId'    => $userId,
            'start'     => $pg_start_page,
            'limit'     => $pageLimit
        );

        if ($status){
            $params['statuses'] = [$status];
        }

        $params['types'] = count($types) > 1 ? 0 : $types[0];

        if($fromDate){
            $fromDate = new \DateTime($fromDate);
            $fromDate = $fromDate->format('Y-m-d');
            $params['fromDate'] = $fromDate;
        }
        if($toDate){
            $toDate = new \DateTime($toDate);
            $toDate  = $toDate->format('Y-m-d');
            $params['toDate'] = $toDate;
        }

        $approvalFlow = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        $params['count']= 1;
        $countApprovalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        if($countApprovalFlowList){
            $countItem = $countApprovalFlowList;
            $pagination = $this->get('TTRouteUtils')->getRelatedDiscoverPagination($countItem, $pageLimit, $page, $count, $class );
        }

        $this->data['allApprovalFlowList'] = $approvalFlow;

        $data = array();
        $data['allApprovalFlow'] = $this->render('@Corporate/admin/manageBookings/corporate-manage-bookings-list.twig', $this->data)->getContent();

        if($pagination){
            $data['pagination'] = $pagination;
        }

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * Action to get an approval
     *
     * @return TWIG
     */
    public function approveAction($reservationId, $moduleId, $accountId , $transactionUserId, $requestServicesDetailsId)
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();

        if (!$accountId) {
            $accountId = $sessionInfo['accountId'];
        }

        $userId = $sessionInfo['userId'];
        $userRole = $sessionInfo['userGroupId'];
        $checkApprove = $sessionInfo['allowApproval'];
        $startLink = '';

        if ($checkApprove || $userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            $parameters = array(
                'reservationId' => $reservationId,
                'moduleId' => $moduleId,
                'userId' => $userId,
                'accountId' => $accountId,
                'transactionUserId' => $transactionUserId,
                'requestServicesDetailsId' => $requestServicesDetailsId
            );
            if ($moduleId == $this->container->getParameter('MODULE_FLIGHTS')) {
                $route = '_corporate_flight_check';
            } elseif ($moduleId == $this->container->getParameter('MODULE_HOTELS')) {
                $route = '_corporate_hotel_reservation_approve';
            } elseif ($moduleId == $this->container->getParameter('MODULE_DEALS')) {
                $route = '_corporate_proceed_booking_with_approval';
            } else {
                return $this->redirectToLangRoute('_corporate_pending_approval');
            }

            return $this->redirectToLangRoute($route, $parameters);

        } else {
            return $this->redirectToLangRoute('_corporate_pending_approval');
        }
    }

    /**
     * controller action to get the reject travel
     *
     * @return TWIG
     */
    public function rejectAction()
    {
        $msg                = '';
        $request = Request::createFromGlobals();
        $accountId = $request->query->get('accountId',0);
        $userId = $request->query->get('userId',0);

        if($accountId == 0 && $userId == 0)
        {
            $sessionInfo        = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId          = $sessionInfo['accountId'];
            $userId             = $sessionInfo['userId'];
        }

        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];

        $request     = Request::createFromGlobals();
        $requestServicesDetailsId   = $request->request->get('requestServicesDetailsId', '');
        $transactionUserId = $request->request->get('transactionUserId', '');

        $parameters         = array(
            'accountId' => $accountId,
            'id'  => $requestServicesDetailsId,
            'requestStatus'    =>$this->container->getParameter('CORPO_APPROVAL_REJECTED')
        );

        $checkApprove = $this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($userId, $transactionUserId, $accountId);
        if ($checkApprove || $userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            $canceled   = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);
            $msg        = $this->translator->trans('success');
        }else{
            $msg        = $this->translator->trans("you don't have enough permission to reject this request");
        }
        $data['msg']    = $msg;

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action to get the cancel travel
     *
     * @return TWIG
     */
    public function cancelAction()
    {
        $msg                = '';
        $sessionInfo        = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId          = $sessionInfo['accountId'];
        $userId             = $sessionInfo['userId'];

        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];

        $request = Request::createFromGlobals();
        $reservationId = $request->request->get('reservationId', '');
        $moduleId = $request->request->get('moduleId', '');
        $transactionUserId = $request->request->get('transactionUserId', '');

        $checkApprove = $this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($userId, $transactionUserId, $accountId);
        if ($checkApprove || $userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            if($moduleId == $this->container->getParameter('MODULE_DEALS')){
                $check = json_decode($this->get('DealServices')->cancelBookingJson($reservationId),true);
            }elseif($moduleId == $this->container->getParameter('MODULE_HOTELS')){
                $check = json_decode($this->get('HotelsServices')->hotelCancellationJson($reservationId),true);
            }elseif($moduleId == $this->container->getParameter('MODULE_FLIGHTS')){
                $check = json_decode($this->get('FlightServices')->flightCancelationAsService($reservationId),true);
            }
            //        print_r($check);exit;
            if($check){
                $success = $check['success'];
                $message = $check['message'];
                $data = $check['data'];
                $cancellationFee = $data['cancellationFee'];
                $cancellationAmount = $cancellationFee['amount'];
                $cancellationCurrency = $cancellationFee['currency'];

                $data['success'] = $success;
                $data['message'] = $message;
                if($success){
                    $parameters['requestStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
                    $parameters['reservationId'] = $reservationId;
                    $parameters['moduleId'] = $moduleId;
                    $parameters['amount'] = 0;
                    $parameters['amountFBC'] = 0;
                    $parameters['amountSBC'] = 0;
                    $parameters['amountAccountCurrency'] = 0;
                    $updateStatus = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);
                    if($cancellationFee){
                        $parameters['amount'] = $cancellationAmount;
                        unset($parameters['amountFBC']);
                        unset($parameters['amountSBC']);
                        unset($parameters['amountAccountCurrency']);

                        $parameters['currencyCode'] = $cancellationCurrency;
                        $parameters['userId'] = $userId;
                        $parameters['accountId'] = $accountId;
                        $status = $this->container->getParameter('CORPO_APPROVAL_APPROVED');

                        $addFees = $this->get('CorpoApprovalFlowServices')->addPendingRequestServices($parameters,$status);
                    }
                }
            }
        }else{
            $data['success'] = false;
            $data['message'] = $this->translator->trans('You have not the permission to cancel this request');
        }

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * Controller to get the total amount of bookings as per request entered
     *
     * @return json
     */
    public function getTotalAction()
    {
        $request = $this->getRequest()->request->all();

        $commonCode = $this->commonCode();
        $sessionAccountId = $commonCode['accountId'];

        $fromDate = isset($request['fromDate']) ? $request['fromDate'] :'';
        $toDate = isset($request['toDate']) ? $request['toDate'] : '';
        $types = isset($request['serviceType']) ? explode(",", $request['serviceType']) : 0;
        $status = isset($request['bookingStatus']) ? $request['bookingStatus'] : 0;
        $accountId = isset($request['accountId']) ? $request['accountId'] : $sessionAccountId;
        $userId = isset($request['userId']) ? $request['userId'] : $commonCode['userId'];

        $params = array(
            'accountId' => $accountId,
            'userId'    => $userId,
        );

        if ($types) {
            $params['types'] = $types;
        }

        if($fromDate){
            $fromDate = new \DateTime($fromDate);
            $fromDate = $fromDate->format('Y-m-d');
            $params['fromDate'] = $fromDate;
        }
        if($toDate){
            $toDate = new \DateTime($toDate);
            $toDate  = $toDate->format('Y-m-d');
            $params['toDate'] = $toDate;
        }

        $totalSum = $this->get('CorpoApprovalFlowServices')->getRequestDetailSumOfAmount($sessionAccountId, $status, $params);

        $data = array();
        $data['total'] = number_format($totalSum['sumAccountAmount'],'2','.',',');

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

}
