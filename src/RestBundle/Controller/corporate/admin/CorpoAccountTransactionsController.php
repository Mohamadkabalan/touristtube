<?php

namespace RestBundle\Controller\corporate\admin;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use RestBundle\Controller\TTRestController;
use \Datetime;

class CorpoAccountTransactionsController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
    }

    /**
     * Method POST
     * Due Payment for an Account
     *
     * @param $accountId
     * @return response
     */
    public function getAccountTransactionsAction($accountId = 0)
    {
        if ($accountId == '' || $accountId == 0) throw new HttpException(400, $this->translator->trans("Invalid account credentials. Please try again."));

        $fromDate = new DateTime('first day of this month');
        $toDate   = new DateTime('last day of this month');

        $firstDay = $fromDate->format('Y-m-d');
        $lastDay  = $toDate->format('Y-m-d');

        // fech post json data
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        // By default get the transactions of the past 1 month from today if fromdate and todate are not present
        $todayDate = new \DateTime("now");
        $toDay     = new \DateTime("-1 month");

        $firstDay = $toDay->format('Y-m-d');
        $lastDay  = $todayDate->format('Y-m-d');

        $params              = array();
        $params['accountId'] = $accountId;
        $params['firstDay']  = $firstDay;
        $params['lastDay']   = $lastDay;

        if (isset($post['type']) && $post['type']) {
            switch (strtolower($post['type'])) {
                case "flights":
                    $params['moduleId'] = $this->container->getParameter('MODULE_FLIGHTS');
                    break;
                case "hotels":
                    $params['moduleId'] = $this->container->getParameter('MODULE_HOTELS');
                    break;
                case "deals":
                    $params['moduleId'] = $this->container->getParameter('MODULE_DEALS');
                    break;
                default:
                    $params['moduleId'] = 0;
                    break;
            }
        }

        if (isset($post['fromdate']) && $post['fromdate']) {
            $date               = date_create($post['fromdate']);
            $params['firstDay'] = date_format($date, 'Y-m-d');
        }

        if (isset($post['todate']) && $post['todate']) {
            $date              = date_create($post['todate']);
            $params['lastDay'] = date_format($date, 'Y-m-d');
        }

        if (isset($post['currency']) && $post['currency']) {
            $params['currencyCode'] = $post['currency'];
        }

        $results = array();

        $results['fromDate'] = $params['firstDay'];
        $results['toDate']   = $params['lastDay'];

        $accountTransactionsList = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountTransactionsList($params);
        if ($accountTransactionsList) {
            foreach ($accountTransactionsList as $list) {

                $item                               = array();
                $item['corpoAccountTransactionsId'] = $list['al_id'];
                $item['accountId']                  = $list['al_accountId'];
                $item['accountName']                = $list['accountName'];
                $item['createdBy']                  = $list['userName'];
                $item['module']['id']               = $list['al_moduleId'];
                $item['module']['name']             = $list['moduleName'];
                $item['fbcCurrencyCode']            = $this->container->getParameter('FBC_CODE');
                $item['amountFBC']                  = $list['al_amountFBC'];
                $item['sbcCurrencyCode']            = $this->container->getParameter('SBC_CODE');
                $item['amountSBC']                  = $list['al_amountSBC'];
                $item['preferredCurrency']          = $list['userPreferredCurrency'];
                $item['amountAccountCurrency']      = $list['al_amountAccountCurrency'];
                $item['amount']                     = $list['al_amount'];
                $item['currency']                   = $list['al_currencyCode'];
                $item['description']                = $list['al_description'];
                $item['status']                     = $list['statusName'];
                $item['date']                       = $list['al_createdAt']->format('m/d/Y');

                $results['transactions'][] = $item;
            }

            // retrieve total
            $listSum          = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionTotals($params);
            $results['total'] = array(
                'sumFBC' => number_format($listSum['sumAmountFBC'], 2, '.', ','),
                'sumSBC' => number_format($listSum['sumAmountSBC'], 2, '.', ','),
                'sumPreferred' => number_format($listSum['sumAccountAmount'], 2, '.', ','),
            );
        }

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * This method returns the account transaction details
     *
     * @param Integer $accountTransactionId
     * @param Integer $moduleId
     * @return response
     */
    public function getAccountTransactionDetailsAction($accountTransactionId, $moduleId)
    {
        if ($moduleId == $this->container->getParameter('MODULE_FLIGHTS')) {
            $info = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionFlightAllInfo($accountTransactionId, $moduleId);
        } elseif ($moduleId == $this->container->getParameter('MODULE_HOTELS')) {
            $info = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionHotelAllInfo($accountTransactionId, $moduleId);
        } elseif ($moduleId == $this->container->getParameter('MODULE_DEALS')) {
            $info = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionDealAllInfo($accountTransactionId, $moduleId);
        } elseif ($moduleId == $this->container->getParameter('MODULE_FINANCE')) {
            $info = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountPaymentAllInfo($accountTransactionId, $moduleId);
        }

        $response = new Response(json_encode($info));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * This method returns the total due amount of an account
     *
     * @param Integer $accountId
     *
     * @return JSON object having the needed data
     */
    public function getAccountTransactionsDueAmountAction($accountId = 0)
    {
        $totalDueAmountCurrency = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);

        $parameters['accountId'] = $accountId;
        $currentBalance          = $this->get('CorpoAccountServices')->getCurrentBalance($parameters);
        $totalDueAmount          = number_format($currentBalance['sumAccountAmount'], 2, '.', ',');

        $return = array("totalDueAmountCurrency" => $totalDueAmountCurrency, "totalDueAmount" => $totalDueAmount);

        $response = new Response(json_encode($return));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Get Approvals for an Account
     *
     * @QueryParam(name="status")
     * @QueryParam(name="page")
     * @QueryParam(name="limit")
     * @param $accountId
     * @param ParamFetcher $paramFetcher
     * @return response
     */
    public function getAccountApprovalsAction($accountId = 0, ParamFetcher $paramFetcher)
    {
        if ($accountId == '' || $accountId == 0) throw new HttpException(400, $this->translator->trans("Invalid account credentials. Please try again."));
        $post = $paramFetcher->all();

        $params = array();
        switch (strtolower($post['status'])) {
            case "pending":
                $params['status'] = $this->container->getParameter('CORPO_APPROVAL_PENDING');
                break;
            case "approved":
                $params['status'] = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
                break;
            case "canceled":
                $params['status'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
                break;
            case "expired":
                $params['status'] = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
                break;
            case "rejected":
                $params['status'] = $this->container->getParameter('CORPO_APPROVAL_REJECTED');
                break;
            default:
                $params['status'] = 0;
                break;
        }

        $pageLimitRecords = $post['limit'];
        $pageStart        = $post['page'];
        $start            = ( $pageStart * $pageLimitRecords ) - $pageLimitRecords;

        $params['start']     = $start;
        $params['limit']     = $pageLimitRecords;
        $params['accountId'] = $accountId;

        $travelApproval = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);

        $results = array();
        if ($travelApproval) {
            foreach ($travelApproval as $list) {

                $item                             = array();
                $item['requestServicesDetailsId'] = $list['requestServicesDetailsId'];
                $item['title']                    = $list['name'];
                $item['Account']['id']            = $list['accountId'];
                $item['Account']['name']          = $list['accountName'];
                $item['requester']['id']          = $list['userId'];
                $item['requester']['username']    = $list['userName'];
                $item['module']['id']             = $list['moduleId'];
                $item['module']['name']           = $list['moduleName'];
                $item['reservationId']            = $list['reservationId'];
                $item['currency']                 = $list['currency'];
                $item['amount']                   = $list['amount'];
                $item['amountFBC']                = $list['amount_fbc'];
                $item['amountSBC']                = $list['amount_sbc'];
                $item['fromdate']                 = $list['fromdate'];
                $item['todate']                   = $list['todate'];
                $item['status']                   = $list['statusName'];
                $item['details']                  = json_decode($list['details']);
                $results[]                        = $item;
            }
        }

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST (ON HOLD FOR NOW UNTIL PAYMENT AND APPROVAL IS DONE)
     * Due Payment for an Account
     *
     * @RequestParam(name="accountId")
     * @RequestParam(name="userId")
     * @RequestParam(name="transactionId")
     * @RequestParam(name="transactionType")
     * @RequestParam(name="requestServicesDetailsId")
     * @param ParamFetcher $paramFetcher
     * @return response
     */
    public function approvedTransactionAction(ParamFetcher $paramFetcher)
    {
        $post = $paramFetcher->all();

        $user      = $this->get('security.token_storage')->getToken()->getUser();
        $logInUser = $user->getId();

        $checkApprove = $this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($logInUser, $post['userId'], $post['accountId']);

        $result = array();
        if ($checkApprove) {

            $moduleId   = $this->container->getParameter('MODULE_'.strtoupper($post['transactionType']));
            $parameters = array(
                'reservationId' => $post['transactionId'],
                'moduleId' => $moduleId,
                'userId' => $logInUser,
                'accountId' => $post['accountId'],
                'transactionUserId' => $post['userId'],
                'requestServicesDetailsId' => $post['requestServicesDetailsId']
            );

            switch (strtolower($post['transactionType'])) {
                case "deals":
                    $serviceName = 'DealServices';
                    break;
                case "hotels":
                    $serviceName = '';
                    break;
                case "flights":
                    $serviceName = '';
                    break;
            }
            $approvedTrx = $this->get($serviceName)->approveTransaction($parameters);
        } else {
            throw new HttpException(433, $this->translator->trans("User is not allowed to approve this request"));
        }

        $approvedTrxDecoded = json_decode($approvedTrx, true);
        $results            = $approvedTrxDecoded['data'];

        $response = new Response(json_encode($results));
        $response->setStatusCode($results['statusCode']);
        return $response;
    }
    /*
     * Api that response a JSON with default my Booking items
     */

    public function corpoMyBookingAction(Request $request)
    {
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $params = array();

        if (isset($post['accountId']) && $post['accountId']) {

            $params['accountId'] = $post['accountId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }
        if (isset($post['userId']) && $post['userId']) {

            $params['userId'] = $post['userId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }

        $params['status'] = 2;

        if (isset($post['types']) && $post['types']) {
            switch (strtolower($post['types'])) {
                case "flights":
                    $params['types'] = $this->container->getParameter('MODULE_FLIGHTS');
                    break;
                case "hotels":
                    $params['types'] = $this->container->getParameter('MODULE_HOTELS');
                    break;
                case "deals":
                    $params['types'] = $this->container->getParameter('MODULE_DEALS');
                    break;
                default:
                    $params['types'] = 0;
                    break;
            }
        }


        if (isset($post['start']) && $post['start']) {
            $params['start'] = $post['start'];
        } else {
            $params['start'] = 0;
        }
        if (isset($post['limit']) && $post['limit']) {
            $params['limit'] = $post['limit'];
        } else {
            $params['limit'] = 10;
        }

        $approvalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if ($approvalFlowList) {
            // initialize default image for each item on our result.
            foreach ($approvalFlowList as &$item) {
                $defaultImg = '';
                switch ($item['moduleId']) {
                    case $this->container->getParameter('MODULE_FLIGHTS'):
                        $flightDetails    = json_decode($item['details'], true);
                        $defaultImg       = $this->get("TTRouteUtils")->generateMediaURL("/media/images/airline-logos/{$flightDetails['logo']}");
                        break;
                    case $this->container->getParameter('MODULE_HOTELS'):
                        $defaultImgParams = array(
                            'dupePoolId' => $item['dupePoolId'],
                            'location' => $item['location'],
                            'filename' => $item['filename']
                        );

                        $defaultImg       = $this->container->get('app.utils')->getDefaultHotelImage($defaultImgParams);
                        break;
                    case $this->container->getParameter('MODULE_DEALS'):
                        $defaultImgParams = array(
                            'apiId' => $item['apiId'],
                            'dealCode' => $item['dealCode'],
                            'categoryName' => $item['categoryName'],
                            'cityName' => $item['cityName'],
                            'imageId' => $item['imageId']
                        );

                        $defaultImg = $this->container->get('app.utils')->generateDefaultDealImage($defaultImgParams);
                        break;
                }

                $item['defaultImage'] = $defaultImg;
            }
        } else {
            $approvalFlowList = array("code" => 500, "message" => "No Available Result for these type of filters");
        }

        $response = new Response(json_encode($approvalFlowList));
        $response->setStatusCode(200);
        return $response;
    }
    /*
     * Currency Auto-complete
     */

    public function corpoCurrencySearchAction(Request $request)
    {
        $term  = $request->query->get('term', '');
        $limit = $request->query->get('limit', 10);

        $currency = $this->get('CorpoAdminServices')->getCorpoAdminLikeCurrency($term, $limit);

        $response = new Response(json_encode($currency));

        $response->setStatusCode(200);

        return $response;
    }
    /*
     * REST API to GET filtered Result of MyBooking
     */

    public function corpoMyBookingFilterAction(Request $request)
    {
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $params = array();

        if (isset($post['accountId']) && $post['accountId']) {

            $params['accountId'] = $post['accountId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }
        if (isset($post['userId']) && $post['userId']) {

            $params['userId'] = $post['userId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }

        $params['status'] = 2;

        if (isset($post['types']) && $post['types']) {
            switch (strtolower($post['types'])) {
                case "flights":
                    $params['types'] = $this->container->getParameter('MODULE_FLIGHTS');
                    break;
                case "hotels":
                    $params['types'] = $this->container->getParameter('MODULE_HOTELS');
                    break;
                case "deals":
                    $params['types'] = $this->container->getParameter('MODULE_DEALS');
                    break;
                default:
                    $params['types'] = 0;
                    break;
            }
        }

        if (isset($post['fromDate']) && $post['fromDate']) {

            $fromDate           = new \DateTime($post['fromDate']);
            $params['fromDate'] = $fromDate->format('Y-m-d');
        }

        if (isset($post['toDate']) && $post['toDate']) {

            $toDate           = new \DateTime($post['toDate']);
            $params['toDate'] = $toDate->format('Y-m-d');
        }

        if (isset($post['currency']) && $post['currency']) {

            $params['currencyCode'] = $post['currency'];
        }

        if (isset($post['start']) && $post['start']) {

            $params['start'] = $post['start'];
        } else {

            $params['start'] = 0;
        }
        if (isset($post['limit']) && $post['limit']) {

            $params['limit'] = $post['limit'];
        } else {

            $params['limit'] = 10;
        }

        $approvalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if (!$approvalFlowList) {
            $approvalFlowList = array("code" => 500, "message" => "No Available Result for these type of filters");
        }
        $response = new Response(json_encode($approvalFlowList));
        $response->setStatusCode(200);
        return $response;
    }
    /*
     * Get Total Amount of Travel Approvals status
     */

    public function sumOfTravelApprovalStatusAction(Request $request)
    {
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $params = array();

        if (isset($post['accountId']) && $post['accountId']) {

            $params['accountId'] = $post['accountId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }
        if (isset($post['status']) && $post['status']) {

            $status = $post['status'];
        } else {

            $status = 0;
        }


        $totalSum = $this->get('CorpoApprovalFlowServices')->getRequestDetailSumOfAmount($params, $status);

        $response = new Response(json_encode($totalSum));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * controller action for getting a list of users
     *
     * @return JSON
     */
    public function adminSearchUserAction()
    {
        $request   = Request::createFromGlobals();
        $term      = strtolower($request->query->get('q', ''));
        $id        = strtolower($request->query->get('excludeId', ''));
        $accountId = strtolower($request->query->get('accountId', ''));

        $limit = 100;
        $data  = $this->get('CorpoAdminServices')->getCorpoAdminLikeUsers($term, $limit, $id, $accountId);

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * Reject api in Travel Approval
     *
     * @return JSON
     */
    public function rejectBookRequestApprovalAction()
    {
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $params = array();

        if (isset($post['accountId']) && $post['accountId']) {

            $params['accountId'] = $post['accountId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }
        if (isset($post['userId']) && $post['userId']) {

            $params['userId'] = $post['userId'];
        } else {

            $error = array("code" => 422, "message" => "missing mandatory parameter");

            $response = new Response(json_encode($error));
            $response->setStatusCode(422);

            return $response;
        }

        $params['status'] = 2;

        if (isset($post['types']) && $post['types']) {
            switch (strtolower($post['types'])) {
                case "flights":
                    $params['types'] = $this->container->getParameter('MODULE_FLIGHTS');
                    break;
                case "hotels":
                    $params['types'] = $this->container->getParameter('MODULE_HOTELS');
                    break;
                case "deals":
                    $params['types'] = $this->container->getParameter('MODULE_DEALS');
                    break;
                default:
                    $params['types'] = 0;
                    break;
            }
        }

        if (isset($post['requestServicesDetailsId']) && $post['requestServicesDetailsId']) {


            $params['requestServicesDetailsId'] = $$post['requestServicesDetailsId'];
        }

        if (isset($post['transactionUserId']) && $post['transactionUserId']) {

            $params['transactionUserId'] = $$post['transactionUserId'];
        }

        $approvalFlowList = $this->get('CorpoApprovalFlowServices')->getAllApprovalFlowList($params);
        if (!$approvalFlowList) {
            $approvalFlowList = array("code" => 500, "message" => "No Available Result for these type of filters");
        }
        $response = new Response(json_encode($approvalFlowList));
        $response->setStatusCode(200);
        return $response;
    }
}
