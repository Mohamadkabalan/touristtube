<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Datetime;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the Account Transactions 
 */
class CorpoAccountTransactionsController extends CorpoAdminController
{

    /**
     * controller action for the list of accounts Transactions
     *
     * @return TWIG
     */
    public function accountTransactionsAction()
    {
        $request    = Request::createFromGlobals();
        $searchUserId     = $request->query->get('userId', '');
        $searchAccountId  = $request->query->get('accountId', '');
        $firstDay     = $request->query->get('fDate', '');
        $lastDay     = $request->query->get('tDate', '');
        $currency = $request->query->get('currency', '');
        $accType = $request->query->get('type', '');

        $userObj    = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $accountId  = $userObj[0]['cu_corpoAccountId'];

        if(!isset($firstDay) || empty($firstDay)) {
            $toDay      = new \DateTime("-1 month");
            $firstDay   = $toDay->format('Y-m-d'); 
        }
        if(!isset($lastDay) || empty($lastDay)) {
            $todayDate  = new \DateTime("now");
            $lastDay    = $todayDate->format('Y-m-d');
        }
        if(isset($currency) && !empty($lastDay)) {
            $this->data['currencyCode'] = $currency;
        }
        if(isset($accType) && $accType != 0) {
            $this->data['acctType'] = $accType;
        }
        
        $modules    = array(
            [
                'name' => $this->translator->trans('All'),
                'id' => $this->container->getParameter('MODULE_DEFAULT')
            ]
        );
        $modules[] = ['name' => $this->translator->trans('Flights'),'id' => $this->container->getParameter('MODULE_FLIGHTS')];
        $modules[] = ['name' => $this->translator->trans('Hotels'),'id' => $this->container->getParameter('MODULE_HOTELS')];
        if ( $this->show_deals_block == 1 ) {
            $modules[] = ['name' => $this->translator->trans('Deals'),'id' => $this->container->getParameter('MODULE_DEALS')];
        }
        $modules[] = ['name' => $this->translator->trans('Accounting'),'id' => $this->container->getParameter('MODULE_FINANCE')];
        
        $parameters['firstDay']         = $firstDay;
        $parameters['lastDay']          = $lastDay;
        if($searchAccountId){
            $parameters['accountId']    = $searchAccountId;
        }else{
            $parameters['accountId']    = $accountId;
        }
        if($searchUserId){
            $parameters['userId']       = $searchUserId;
        }
        
        $this->data['firstDay']         = $firstDay;
        $this->data['lastDay']          = $lastDay;
        $this->data['amountFBC']        = $this->container->getParameter('FBC_CODE');
        $this->data['amountSBC']        = $this->container->getParameter('SBC_CODE');
        $this->data['preferredCurrency']= $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $listSum                        = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionTotals($parameters);
        $this->data['sumFBC']           = number_format($listSum['sumAmountFBC'], 2, '.', ',');
        $this->data['sumSBC']           = number_format($listSum['sumAmountSBC'], 2, '.', ',');
        $this->data['sumPreferred']     = number_format($listSum['sumAccountAmount'], 2, '.', ',');
        $this->data['accountTransactionsList'] = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountTransactionsList($parameters);
        $this->data['cancelledStat'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
        if($userObj[0]['cu_cmsUserGroupId'] == $this->container->getParameter('ROLE_SYSTEM')){
            $this->data['role']             = $this->container->getParameter('ROLE_SYSTEM');
            if(!$searchUserId){
            $accountInfo        = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
            $accountName = $accountInfo['al_name'];
            $this->data['accountName'] = $accountName;
            $this->data['searchAccountId'] = $accountId;
            }
        }
        $this->data['modules']          = $modules;
        if($searchAccountId){
            $accountInfo        = $this->get('CorpoAccountServices')->getCorpoAdminAccount($searchAccountId);
            $accountName = $accountInfo['al_name'];
            $this->data['accountName'] = $accountName;
            $this->data['searchAccountId'] = $searchAccountId;
        }
        if($searchUserId){
            $userInfo = $this->get('UserServices')->getUserDetails(array('id' => $searchUserId));
            $userName = $userInfo[0]['cu_fullname'];
            $this->data['searchUserName'] = $userName;
            $this->data['searchUserId'] = $searchUserId;
        }
        return $this->render('@Corporate/admin/accountTransactions/accountTransactionsList.twig', $this->data);
    }

    public function getDataTableAction()
    {
        $dtService = $this->get('CorpoDatatable');
        $request   = $this->get('request')->request->all();

        $addWhere = "";
        if (isset($request['params']['userId']) && intval($request['params']['userId']) != 0) {
            $addWhere .= " AND cat.created_by = ".$request['params']['userId'];
        }
        if (isset($request['params']['accountId']) && intval($request['params']['accountId']) != 0) {
            $addWhere .= " AND cat.account_id = ".$request['params']['accountId'];
        } else {
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId = $sessionInfo['accountId'];
            $addWhere .= " AND cat.account_id = $accountId";
        }

        if (isset($request['params']['fromDate']) && $request['params']['fromDate'] != '') {
            $firstDay = date('Y-m-d', strtotime($request['params']['fromDate']));
            $addWhere .= " AND DATE(cat.created_at) >= '".$firstDay."'";
        } else {
            $firstDay = date('Y-m-d', strtotime('-1 months'));
            $addWhere .= " AND DATE(cat.created_at) >= '".$firstDay."'";
        }
        if (isset($request['params']['toDate']) && $request['params']['toDate'] != '') {
            $lastDay  = date('Y-m-d', strtotime($request['params']['toDate']));
            $addWhere .= " AND DATE(cat.created_at) <= '".$lastDay."'";
        } else {
            $lastDay  = date('Y-m-d');
            $addWhere .= " AND DATE(cat.created_at) <= '".$lastDay."'";
        }
        if (isset($request['params']['acctType']) && intval($request['params']['acctType']) != 0) {
            $addWhere .= " AND tt.id = ".$request['params']['acctType'];
        }
        if (isset($request['params']['currencyCode']) && $request['params']['currencyCode'] != '') {
            $addWhere .= " AND cat.currency_code = '".$request['params']['currencyCode']."'";
        }
        if (isset($request['params']['serviceType']) && ($request['params']['serviceType'] != '' && $request['params']['serviceType'] != 0)) {
            $addWhere .= " AND cat.module_id = ".$request['params']['serviceType'];
        }

        if (isset($request['params']['accounTypeId']) && !empty($post['accounTypeId'])) {
            $addWhere .= " AND ca.account_type_id = ".$request['params']['accounTypeId'];
        }

        $sql = $this->get('CorpoAccountTransactionsServices')->prepareTransactionsDtQuery();

        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => $addWhere.$sql->where,
            'table' => 'corpo_account_transactions',
            'key' => 'cat.id',
            'columns' => NULL,
        );

        $queryArr = $dtService->buildQuery($params);

        $transactions = $dtService->runQuery($queryArr);

        return new JsonResponse($transactions);
    }

    public function getDataTableSumAction(Request $request)
	{
        $parameters['moduleId'] = $request->query->get('serviceType', '');
        $parameters['currencyCode'] = $request->query->get('currencyCode', '');
        $parameters['accountId'] = $request->query->get('accountId', '');
        $parameters['userId'] = $request->query->get('userId', '');
        $parameters['firstDay'] = $request->query->get('fromDate', '');
        $parameters['lastDay'] = $request->query->get('toDate', '');
        
        $result = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionTotals($parameters);
        
        return new JsonResponse($result);
	}

    /**
     * controller action for filtering account transactions
     *
     * @return TWIG
     */
    public function filterAccountTransactionAction()
    {
        $request      = Request::createFromGlobals();
        $data         = array();
        $data2        = array();
        $fromDate     = new \DateTime($request->request->get('firstDay', ''));
        $toDate       = new \DateTime($request->request->get('lastDay', ''));
        $accountId    = $request->request->get('accountId', '');
        $userId       = $request->request->get('userId', '');
        $currencyCode = $request->request->get('currencyCode', '');
        $types        = $request->request->get('types', '');
        $firstDay = $fromDate->format('Y-m-d');
        $lastDay  = $toDate->format('Y-m-d');
        if (!$accountId && $types != $this->container->getParameter('MODULE_FINANCE')) {
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId   = $sessionInfo['accountId'];
        }

        $parameters['firstDay']           = $firstDay;
        $parameters['lastDay']            = $lastDay;
        $parameters['moduleId']           = $types;
        $parameters['currencyCode']       = $currencyCode;
        $parameters['accountId']          = $accountId;
        $parameters['userId']             = $userId;
        $data['firstDay']                 = $firstDay;
        $data['lastDay']                  = $lastDay;
        $data['types']                    = $types;
        $data['currencyCode']             = $currencyCode;
        $listSum                          = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionTotals($parameters);

        $data2['amountFBC']               = $this->container->getParameter('FBC_CODE');
        $data2['amountSBC']               = $this->container->getParameter('SBC_CODE');
        $data2['preferredCurrency']       = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $data2['sumFBC']                  = number_format($listSum['sumAmountFBC'],2,'.',',');
        $data2['sumSBC']                  = number_format($listSum['sumAmountSBC'],2,'.',',');
        $data2['sumPreferred']            = number_format($listSum['sumAccountAmount'],2,'.',',');
        $accountTransactionsList          = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountTransactionsList($parameters);
        $data2['accountTransactionsList'] = $accountTransactionsList;
        $data['accountTransactionsList'] = $this->render('@Corporate/admin/accountTransactions/accountTransactionsTableList.twig', $data2)->getContent();
        
        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for adding or  edditing an account Transactions
     *
     * @return TWIG
     */
    public function accountTransactionsAddEditAction($id)
    {
        $this->data['accountTransactions'] = array();
        if ($id) {
            $this->data['accountTransactions'] = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountTransactionsbyId($id);
        }
        return $this->render('@Corporate/admin/accountTransactions/accountTransactionsEdit.twig', $this->data);
    }

    /**
     * controller action for showing the details of account transactions
     *
     * @return TWIG
     */
    public function showAccountTransactionDetailAction()
    {
        $request  = Request::createFromGlobals();
        $id       = $request->request->get('id', '');
        $accountId = $request->request->get('accountId', '');
        $moduleId = $request->request->get('moduleId', '');

        $html = '';
        $title = '';

        if ($moduleId == $this->container->getParameter('MODULE_FLIGHTS')) {

            $flightreservationInfo = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionFlightAllInfo($id, $moduleId);
            $title = sprintf("%s - %s (%s)", $flightreservationInfo[0]['customer'],  $flightreservationInfo[0]['date'], $id);

            $flightDetails = json_decode($flightreservationInfo[0]['details']);

            $type = "One way";

            if (!$flightDetails->oneWay && !$flightDetails->multiDestination){
                $type = "Round trip";
            } elseif ($flightDetails->oneWay && $flightDetails->multiDestination){
                $type = "Multi destination";
            }

            $html .= '
              <b>Booking Details: </b> (Ref# <b>'. $flightDetails->reference .'</b>) - ' .$type. '<br/>
              <b>Booking Date: </b> '. $flightreservationInfo[0]['date']. '<br/>
              <b>Amount:</b> '. $flightreservationInfo[0]['currency'].$flightreservationInfo[0]['amount']. '
              <br/>
              <br/>';

            $html .= '
              <p><b>Flight Details:</b></p>
              <table class="table table-bordered" width="100px;">
                <thead>
                  <tr>
                    <th>Flight #</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Stops</th>
                    <th>Duration</th>
                  </tr>';

            $flightDetailsInfo = [];

            foreach ($flightreservationInfo as $key => $flight) {
                $flightDetails = json_decode($flight['details']);
                $flightDetailsInfo[$key] = $flightDetails;

                $departureDatetime = new \DateTime($flightDetails->departureDatetime);
                $arrivalDatetime = new \DateTime($flightDetails->arrivalDatetime);
                $stopIndicator = $flightDetails->stopIndicator;
                $stopDuration = $flightDetails->stopDuration;

                $html .= '      
                  <tr>  
                    <td>'.$flightDetails->airline.$flightDetails->flightNumber.'</td>
                    <td>'.$flightDetails->departureAirport.'<br/>'. $departureDatetime->format('H:i l, Y-m-d') .'</td>
                    <td>'.$flightDetails->arrivalAirport.'<br/>'. $arrivalDatetime->format('H:i l, Y-m-d').'</td>
                    <td>'.$stopIndicator.'<br/>'. $stopDuration.'</td>
                    <td>'.$flightDetails->flightDuration.'</td>
                  </tr>';
            }

            $html .= '</thead></table>';

            $html .= '
                <p><b>Passsengers:</b></p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>';

            $passengers = [];

            foreach ($flightreservationInfo as $key => $flight) {
                $passengerDetails = json_decode($flight['details']);

                if (in_array($passengerDetails->firstName.' '.$passengerDetails->surname, $passengers)) {
                    continue;
                }

                $passengers[$key] = $passengerDetails->firstName.' '.$passengerDetails->surname;

                $html .= '
                    <tr>
                        <td width="300px">' .($key+1). '. ' .$passengers[$key]. '</td>
                        <td>'. $passengerDetails->passengerType .'</td>
                    </tr>
                    ';
            }

            $html .= '</tbody></table>';

        } elseif ($moduleId == $this->container->getParameter('MODULE_HOTELS')) {

            $hotelreservationInfo = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionHotelAllInfo($id, $moduleId);
            $hotel                = $hotelreservationInfo[0];
            $title = sprintf("%s - %s (%s)", $hotel['customer'],  $hotel['date'], $id);

            $html                 .= '
                        <div class="hotelInfo">
                            <div class="hotelAccTrxTitle">'.$hotel['name'].'</div>
                            <br>
                            <div class="hotelAccTrxLocation">Location : '.$hotel['city'].','.$hotel['country'].'</div>
                            <br>
                            <div class="hoteladdress">Address : '.$hotel['address'].'</div>
                            <br>
                            <div class="hotelAccTrxDate">From: '.$hotel['fromdate'].'</div>
                            <br>
                            <div class="hotelAccTrxDate">To: '.$hotel['todate'].'</div>
                            <br>
                            <div class="hotelAccTrxAmount">Amount : '.$hotel['currency'].'  '.$hotel['amount'].'</div>
                        </div>
                    ';
        } elseif ($moduleId == $this->container->getParameter('MODULE_DEALS')) {

            $dealreservationInfo = $this->get('CorpoAccountTransactionsServices')->getAccountTransactionDealAllInfo($id, $moduleId);
            $deal                = $dealreservationInfo[0];
            $title = sprintf("%s - %s (%s)", $deal['customer'],  $deal['date'], $id);

            $html                .= '
                        <div class="dealInfo">
                            <div class="dealAccTrxTitle">'.$deal['name'].'</div>
                            <br>
                            <div class="dealAccTrxLocation">Location : '.$deal['city'].','.$deal['country'].'</div>
                            <br>
                            <div class="dealAddress">Address : '.$deal['address'].'</div>
                            <br>
                            <div class="dealAccTrxDate">From: '.$deal['fromdate'].'</div>
                            <br>
                            <div class="dealAccTrxDate">To: '.$deal['todate'].'</div>
                            <br>
                            <div class="dealAccTrxAmount">Amount : '.$deal['currency'].'  '.$deal['amount'].'</div>
                        </div>
                    ';
        }elseif ($moduleId == $this->container->getParameter('MODULE_FINANCE')) {
            $financeInfo = $this->get('CorpoAccountTransactionsServices')->getCorpoAdminAccountPaymentAllInfo($id, $accountId, $moduleId);
            $finance              = $financeInfo[0];
            $html                .= '
                        <div class="dealInfo">
                            <div class="dealAccTrxTitle">Account : '.$finance['accountName'].'</div>
                            <br>
                            <div class="dealAddress">User : '.$finance['userName'].'</div>
                            <br>
                            <div class="dealAccTrxDate">description: '.$finance['description'].'</div>
                            <br>
                            <div class="dealAccTrxAmount">Amount : '.$finance['currency_code'].'  '.$finance['sumAmt'].'</div>
                        </div>
                    ';
        }
        $data['title'] = $title;
        $data['htmlDetail'] = $html;

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for submiting either the add or edit of account Transactions
     *
     * @return TWIG
     */
    public function accountTransactionsAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoAccountTransactionsServices')->updateAccountTransactions($parameters);
            if ($success == 0) {
                return $this->redirectToLangRoute('_corpo_account_transactions');
            } else {
                return $this->redirectToLangRoute('_corpo_account_transactions_edit', array('id' => $parameters['id']));
            }
        } else {
            $success = $this->get('CorpoAccountTransactionsServices')->addAccountTransactions($parameters);
            if ($success) {
                return $this->redirectToLangRoute('_corpo_account_transactions');
            } else {
                return $this->redirectToLangRoute('_corpo_account_transactions_add');
            }
        }
    }

    /**
     * controller action for deleting an account Transactions
     *
     * @return TWIG
     */
    public function accountTransactionsDeleteAction($id)
    {
        $success = $this->get('CorpoAccountTransactionsServices')->deleteCorpoAccountTransactions($id);
        return $this->redirectToLangRoute('_corpo_account_transactions');
    }
}
