<?php

namespace CorporateBundle\Controller;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FlightBundle\Services\FlightServices;


class MyBookingsController extends CorporateController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
                
    }

    
    /**
     * controller action for my bookings
     *
     * @return Twig
     */
    public function corporateMyBookingsAction() {
        $commonCode = $this->commonCode();
        $countItem = $commonCode['countItem'];
        $pg_start_page = 0;
        $page = 1;
        $pg_limit_records = $commonCode['pg_limit_records'];
        $count = $commonCode['count'];
        $class=$commonCode['class'];
        $pagination= '';
        $modules = $commonCode['modules'];
        $accountId                              = $commonCode['accountId'];
        $userId                                 = $commonCode['userId'];
        $parameters['accountId']                = $accountId;
        $parameters['userId']                   = $userId;
        $status                                 = $commonCode['status'];
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
            'status' => $status,
            'start'  => $pg_start_page,
            'limit'  => $pg_limit_records
        );
        $userObj    = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        if($userObj[0]['cu_cmsUserGroupId'] == $this->container->getParameter('ROLE_SYSTEM')){
            $this->data['role']             = $this->container->getParameter('ROLE_SYSTEM');
        }
        $allApprovalFlowList            = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        $params['count']= 1;
        $countApprovalFlowList          = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if($countApprovalFlowList){
            $countItem = $countApprovalFlowList;
            $pagination                         = $this->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page,$count ,$class );
        } 
        $this->data['pagination']               = $pagination;
        $this->data['allApprovalFlowList']      = $allApprovalFlowList;
        
        return $this->render('@Corporate/corporate/corporate-my-bookings.twig', $this->data);
    }
    
    /**
     * controller action for filtering my bookings
     *
     * @return json rendering a twig
     */
    public function filterMyBookingsAction()
    {
        $request      = Request::createFromGlobals();
        $data         = array();
        $data2        = array();
        $fromDate     = $request->request->get('fromDay', '');
        $toDate       = $request->request->get('toDay', '');
        $accountId    = $request->request->get('accountId', '');
        $userId       = $request->request->get('userId', '');
        $currencyCode = $request->request->get('currencyCode', '');
        $types        = $request->request->get('types', '');
        $pg_start_page = $request->request->get('start', '');
        
        if(!$pg_start_page)
            $pg_start_page = 1;
        
        $commonCode     = $this->commonCode();
        $countItem      = $commonCode['countItem'];
        
        if($fromDate){
            $fromDate = new \DateTime($fromDate);
            $fromDate = $fromDate->format('Y-m-d');
        }
        if($toDate){
            $toDate = new \DateTime($toDate);
            $toDate  = $toDate->format('Y-m-d');
        }
        $class       = $commonCode['class'];
        $pg_limit_records = $commonCode['pg_limit_records'];
        $page = $pg_start_page;
        $count = $commonCode['count'];
        $pagination  = '';
        $pg_start_page       = ( $pg_start_page * $pg_limit_records ) - $pg_limit_records;
        $modules    = $commonCode['modules'];
        $accountId                         = $commonCode['accountId'];
        if(!$userId){
            $userId                        = $commonCode['userId'];
        }
        $parameters['accountId']           = $accountId;
        $parameters['userId']              = $userId;
        $status                            = $commonCode['status'];
        $data2['preferredAccountCurrency'] = $commonCode['preferredAccountCurrency'];
        $data2['hotelModuleId']            = $commonCode['hotelModuleId'];
        $data2['flightModuleId']           = $commonCode['flightModuleId'];
        $data2['dealModuleId']             = $commonCode['dealModuleId'];
        $data2['approvedStatus']           = $commonCode['approvedStatus'];
        $data2['accountId']                = $accountId;
        $data2['modules']                  = $modules;
        $params                                 = array(
            'accountId' => $accountId,
            'userId'    => $userId,
            'currencyCode'  => $currencyCode,
            'fromDate'  => $fromDate,
            'toDate'    => $toDate,
            'types'     => $types,
            'status'    => $status,
            'start'     => $pg_start_page,
            'limit'     => $pg_limit_records
        );
        $approvalFlow                   = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        
        $params['count']= 1;
        $countApprovalFlowList          = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if($countApprovalFlowList){
            $countItem                  = $countApprovalFlowList;
            $pagination                 = $this->getRelatedDiscoverPagination($countItem, $pg_limit_records, $page,$count ,$class );
        }
        
        $data2['allApprovalFlowList']      = $approvalFlow;
        $data['allApprovalFlow']       = $this->render('@Corporate/corporate/corporate-my-bookingsInfo.twig', $data2)->getContent();
        
        if($pagination){
            $data['pagination']             = $pagination;
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
    private function commonCode(){
        $commonArray = array();
        
        $sessionInfo                            = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId                              = $sessionInfo['accountId'];
        $userId                                 = $sessionInfo['userId'];
        $pg_limit_records = $this->container->getParameter('TRAVEL_APPROVAL_NUMBER_OF_RECORDS');
        $count = $this->container->getParameter('TRAVEL_APPROVAL_PAGINATION_COUNT');
        $class="approval_pagination";
        $countItem    = 0;
        
        $modules    = array(
            [
                'name' => $this->translator->trans('All Bookings'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ],
            [
                'name' => $this->translator->trans('Flights'),
                'id' => $this->container->getParameter('MODULE_FLIGHTS')
            ],
            [
                'name' => $this->translator->trans('Hotels'),
                'id' => $this->container->getParameter('MODULE_HOTELS')
            ]            
        );
        if ( $this->show_deals_block == 1 ) {
            $modules[] = ['name' => $this->translator->trans('Deals'),'id' => $this->container->getParameter('MODULE_DEALS')];
        }
        $status                   = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
        $preferredAccountCurrency = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $hotelModuleId            = $this->container->getParameter('MODULE_HOTELS');
        $flightModuleId           = $this->container->getParameter('MODULE_FLIGHTS');
        $dealModuleId             = $this->container->getParameter('MODULE_DEALS');
        $approvedStatus           = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
        
        $commonArray['accountId'] = $accountId;
        $commonArray['userId'] = $userId;
        $commonArray['pg_limit_records'] = $pg_limit_records;
        $commonArray['count'] = $count;
        $commonArray['class'] = $class;
        $commonArray['countItem'] = $countItem;
        $commonArray['modules'] = $modules;
        $commonArray['status'] = $status;
        $commonArray['hotelModuleId'] = $hotelModuleId;
        $commonArray['flightModuleId'] = $flightModuleId;
        $commonArray['dealModuleId'] = $dealModuleId;
        $commonArray['approvedStatus'] = $approvedStatus;
        $commonArray['preferredAccountCurrency'] = $preferredAccountCurrency;
        
        return $commonArray;
    }
}
