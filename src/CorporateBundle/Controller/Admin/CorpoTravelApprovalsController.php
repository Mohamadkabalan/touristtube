<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller receiving actions related to the accounts
 */
class CorpoTravelApprovalsController extends CorpoAdminController
{

    /**
     * controller action for the list of Travel Approvals
     *
     * @return TWIG
     */
    public function adminTravelApprovalsAction()
    {
        $countItem = 0;
        $pg_start_page = 0;
        $pg_limit_records = $this->container->getParameter('TRAVEL_APPROVAL_NUMBER_OF_RECORDS');
        $page = 1;
        $count = $this->container->getParameter('TRAVEL_APPROVAL_PAGINATION_COUNT');
        $pagination= '';
        $sessionInfo                            = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId                              = $sessionInfo['accountId'];
        $accountInfo        = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
        $accountName = $accountInfo['al_name'];
        $paramter = array(
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
        $changeToExpiry = $this->get('CorpoApprovalFlowServices')->updateExpiredRecords($paramter); 
        $class="approval_pagination";
        $statuses                               = $this->get('CorpoApprovalFlowServices')->getApprovalFlowAllStatus();
        $this->data['statuses']                 = $statuses;
        $parameters['accountId']                = $accountId;
        $this->data['accountName']              = $accountName;
        $status                                 = $this->container->getParameter('CORPO_APPROVAL_PENDING');
        $totalSum                               = $this->get('CorpoApprovalFlowServices')->getRequestDetailSumOfAmount($parameters, $status);
        $this->data['preferredAccountCurrency'] = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $this->data['hotelModuleId']            = $this->container->getParameter('MODULE_HOTELS');
        $this->data['flightModuleId']           = $this->container->getParameter('MODULE_FLIGHTS');
        $this->data['dealModuleId']             = $this->container->getParameter('MODULE_DEALS');
        $this->data['expiredStatus']            = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
        $this->data['approvedStatus']           = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
        $this->data['canceledStatus']           = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
        $this->data['approveStatus']            = $this->container->getParameter('CORPO_APPROVAL_PENDING');
        $this->data['rejectedStatus']           = $this->container->getParameter('CORPO_APPROVAL_REJECTED');
        $this->data['FLIGHT_LAST_HOUR_TO_CANCEL'] = $this->container->getParameter('FLIGHT_LAST_HOUR_TO_CANCEL');
        $this->data['sumPreferredAccountAmout'] = $totalSum['sumAccountAmount'];
        $params                                 = array(
            'accountId' => $accountId,
            'status' => $status,
            'start'  => $pg_start_page,
            'limit'  => $pg_limit_records
        );
        $approvalFlowList                       = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        
        $params['count']= 1;
        $countApprovalFlowList          = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if($countApprovalFlowList){
            $countItem = $countApprovalFlowList;
            $pagination                         = $this->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page,$count ,$class );
        }
        $this->data['pagination']               = $pagination;
        $this->data['travelApproval']           = $approvalFlowList;

        $modules = array(
            [
                'name' => $this->translator->trans('All Bookings'),
                'id' => $this->container->getParameter('MODULE_DEFAULT'),
            ],
            [
                'name' => $this->translator->trans('Flights'),
                'id' => $this->container->getParameter('MODULE_FLIGHTS'),
            ],
            [
                'name' => $this->translator->trans('Hotels'),
                'id' => $this->container->getParameter('MODULE_HOTELS'),
            ],
            [
                'name' => $this->translator->trans('Deals'),
                'id' => $this->container->getParameter('MODULE_DEALS'),
            ],
        );

        $this->data['modules'] = $modules;

        return $this->render('@Corporate/admin/travelApprovals/travelApprovals.twig', $this->data);
    }

    /**
     * controller action to get the approval of any transaction
     *
     * @return TWIG
     */
    public function adminTravelApprovalsApprvoedAction($reservationId, $moduleId, $accountId , $transactionUserId, $requestServicesDetailsId)
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        if (!$accountId) {
            $accountId = $sessionInfo['accountId'];
        }
        $userId       = $sessionInfo['userId'];
        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $accountId = $userObj[0]['cu_corpoAccountId'];
        $userId = $userObj[0]['cu_id'];
        $userRole = $userObj[0]['cu_cmsUserGroupId'];
        $checkApprove = $this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($userId, $transactionUserId, $accountId);
        $startLink    = '';
        if ($checkApprove || $userRole == $this->container->getParameter('ROLE_SYSTEM')) {
//            print_r(1);exit;
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
     * controller action to get the approve all
     *
     * @return TWIG
     */
    public function adminTravelApproveAllAction()
    {
        $sessionInfo        = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId          = $sessionInfo['accountId'];
        $userId             = $sessionInfo['userId'];

        $parameters         = array(
                    'accountId' => $accountId,
                    'moduleId'  => $this->container->getParameter('MODULE_FLIGHTS'),
                    'status'    =>$this->container->getParameter('CORPO_APPROVAL_PENDING')
        );
        $flightsToApprove    = $this->get('CorpoApprovalFlowServices')->getPendingRequest($parameters);

        $parameters['moduleId'] = $this->container->getParameter('MODULE_HOTELS');
        $hotelsToApprove     = $this->get('CorpoApprovalFlowServices')->getPendingRequest($parameters);

        if ( $this->show_deals_block == 1 ) {
            $parameters['moduleId'] = $this->container->getParameter('MODULE_DEALS');
            $dealsToApprove      = $this->get('CorpoApprovalFlowServices')->getPendingRequest($parameters);
        }

        $checkapproval = '';

        return $this->redirectToLangRoute('_corpo_travel_approvals');
    }

    /**
     * controller action for showing the details of account transactions
     *
     * @return TWIG
     */
    public function filterTravelApprovalAction()
    {

        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId   = $sessionInfo['accountId'];

        $request     = Request::createFromGlobals();
        $status      = $request->request->get('status', '');
        $pg_start_page = $request->request->get('start', '');
        $createdBy = $request->request->get('createdBy');
        $createdDate = $request->request->get('createdDate');
        $types = $request->request->get('types');

        $page        = $pg_start_page;
        $count       = $this->container->getParameter('TRAVEL_APPROVAL_PAGINATION_COUNT');
        $pg_limit_records = $this->container->getParameter('TRAVEL_APPROVAL_NUMBER_OF_RECORDS');
        $class       = "approval_pagination";
        $pagination  = '';
        $pg_start_page       = ( $pg_start_page * $pg_limit_records ) - $pg_limit_records;
        if(!$status){
            $status = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
        }

        $data  = array();
        $data2 = array();
        $pendingstatus = $this->container->getParameter('CORPO_APPROVAL_PENDING');

        $params                  = array(
            'accountId' => $accountId,
            'statusPendingValue' => $pendingstatus,
            'status' => $status,
            'start'  => $pg_start_page,
            'created_by' => $createdBy,
            'created_at' => $createdDate,
            'types' => $types,
            'limit'  => $pg_limit_records
        );
        $travelApproval                 = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        $params['count']= 1;
        $countApprovalFlowList          = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if($countApprovalFlowList){
            $countItem                  = $countApprovalFlowList;
            $pagination                 = $this->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page,$count ,$class );
        }

        $totalSum                       = $this->get('CorpoApprovalFlowServices')->getRequestDetailSumOfAmount($params, $status);
        $data['preferredAccountCurrency'] = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $data['sumPreferredAccountAmout'] = number_format($totalSum['sumAccountAmount'], 2, '.', ',');
        $this->data['travelApproval']   = $travelApproval;
        $this->data['hotelModuleId']    = $this->container->getParameter('MODULE_HOTELS');
        $this->data['flightModuleId']   = $this->container->getParameter('MODULE_FLIGHTS');
        $this->data['dealModuleId']     = $this->container->getParameter('MODULE_DEALS');
        $this->data['expiredStatus']    = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
        $this->data['approvedStatus']   = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
        $this->data['canceledStatus']   = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
        $this->data['rejectedStatus']   = $this->container->getParameter('CORPO_APPROVAL_REJECTED');
        $data['travelApproval']         = $this->render('@Corporate/admin/travelApprovals/travelApprovalsInfo.twig', $this->data)->getContent();

        $data['pagination']             = $pagination;
        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action to get the reject travel
     *
     * @return TWIG
     */
    public function adminTravelApprovalRejectAction()
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
        $transactionUserId          = $request->request->get('transactionUserId', '');

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
    public function adminTravelApprovalCancelAction()
    {
        $msg                = '';
        $sessionInfo        = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId          = $sessionInfo['accountId'];
        $userId             = $sessionInfo['userId'];

        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];

        $request     = Request::createFromGlobals();
        $reservationId              = $request->request->get('reservationId', '');
        $moduleId                   = $request->request->get('moduleId', '');
        
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
            $data['message'] = $this->translator->trans('You have not the permission to reject this request');
        }
            
        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

}