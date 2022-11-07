<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Datetime;
use CorporateBundle\Model\Payment;
use CorporateBundle\Model\Transactions;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the Account Payment
 */
class CorpoAccountPaymentController extends CorpoAdminController
{

    /**
     * controller action for the list of accounts Payment
     *
     * @return TWIG
     */
    public function accountPaymentAction()
    {
        $request    = Request::createFromGlobals();
        $firstDay     = $request->query->get('fDate', '');
        $lastDay     = $request->query->get('tDate', '');
        $accountId  = $request->query->get('accountId', '');

        if(empty($accountId)) {
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId = $sessionInfo['accountId'];
        }
        if(empty($firstDay)) {
            $currDate = date('Y-m-d');
            $firstDay = date('Y-m-d', strtotime($currDate . '-1 month'));
        }
        if(empty($lastDay)) {
            $tDate  = new \DateTime("now");
            $lastDay    = $tDate->format('Y-m-d');
        }
        $parameters['accountId']        = $accountId;
        $parameters['firstDay']         = $firstDay;
        $parameters['lastDay']          = $lastDay;
        
        $this->data['accountId']        = $accountId;
        $this->data['firstDay']         = $firstDay;
        $this->data['lastDay']          = $lastDay;
        $listSum                        = $this->get('CorpoAccountPaymentServices')->getAccountPaymentTotals($parameters);
        $this->data['amountFBC']        = $this->container->getParameter('FBC_CODE');
        $this->data['amountSBC']        = $this->container->getParameter('SBC_CODE');
        $this->data['accountPaymentList'] = array();
        $this->data['preferredCurrency']  = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($accountId);
        $this->data['sumFBC']            = number_format($listSum['sumAmountFBC'], 2, '.', ',');
        $this->data['sumSBC']            = number_format($listSum['sumAmountSBC'], 2, '.', ',');
        $this->data['sumPreferred']      = number_format($listSum['sumAccountAmount'], 2, '.', ',');
        $accountPaymentList              = $this->get('CorpoAccountPaymentServices')->getCorpoAdminAccountPaymentList($parameters);
        $this->data['accountPaymentList'] = $accountPaymentList;
        
        return $this->render('@Corporate/admin/accountPayment/accountPaymentList.twig', $this->data);
    }

    public function getDataTableAction()
    {
        $dtService = $this->get('CorpoDatatable');
        $request   = $this->get('request')->request->all();

        //this is for datatables
        if (isset($request['params'])) {
            $post = $request['params'];
        } else {
            $post = $request;
        }

        $sql = $this->get('CorpoAccountPaymentServices')->preparePaymentDtQuery();

        $addWhere = "";
        if (isset($post['accountId']) && !empty($post['accountId'])) {
            $addWhere .= " AND cap.account_id = ".$post['accountId'];
        } else {
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountId   = $sessionInfo['accountId'];
            $addWhere    .= " AND cap.account_id = $accountId";
        }
        if (isset($post['fromDate']) && !empty($post['fromDate'])) {
            $firstDay = date('Y-m-d', strtotime($post['fromDate']));
            $addWhere .= " AND DATE(cap.payment_date) >= '".$firstDay."'";
        } else {
            $fromDate = new DateTime('first day of this month');
            $firstDay = $fromDate->format('Y-m-d');
            $addWhere .= " AND DATE(cap.payment_date) >= '".$firstDay."'";
        }
        if (isset($post['toDate']) && !empty($post['toDate'])) {
            $lastDay  = date('Y-m-d', strtotime($post['toDate']));
            $addWhere .= " AND DATE(cap.payment_date) <= '".$lastDay."'";
        } else {
            $toDate   = new DateTime('last day of this month');
            $lastDay  = $toDate->format('Y-m-d');
            $addWhere .= " AND DATE(cap.payment_date) <= '".$lastDay."'";
        }

        if (isset($post['accountTypeId']) && !empty($post['accountTypeId'])) {
            $addWhere .= " AND ca.account_type_id = ".$post['accountTypeId'];
        }
        
        $params   = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => $addWhere . $sql->where,
            'table' => 'corpo_account_payment',
            'key' => 'cap.id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $payment  = $dtService->runQuery($queryArr);

        return new JsonResponse($payment);
    }

    /**
     * controller action for adding or  edditing an account Payment
     *
     * @return TWIG
     */
    public function accountPaymentAddEditAction($id)
    {
        $id =strval($id);
        $newId = explode('/',$id );
        $id = $newId[0];
        if(isset($newId[1])){
            $autoClose = $newId[1];
        }else{
            $autoClose = '';
        }
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $userId = $sessionInfo['userId'];
        
        $userInfo = $this->get('UserServices')->getUserInfoById($userId, false);
        if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));
        
        $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($sessionInfo['accountId']);
        $userName = $userInfo[0]->getFullname();
        $this->data['accountId']   = $sessionInfo['accountId'];
        $this->data['accountName'] = $accountInfo['al_name'];
        $this->data['autoClose'] = $autoClose;
        $this->data['userName'] = $userName;
        $this->data['userId'] = $userId;

        $this->data['accountPayment'] = array();
        if ($id) {
            $this->data['accountPayment'] = $this->get('CorpoAccountPaymentServices')->getCorpoAdminAccountPaymentbyId($id);
            $this->data['accountPayment']['al_amount'] = abs($this->data['accountPayment']['al_amount']);
        }
        return $this->render('@Corporate/admin/accountPayment/accountPaymentEdit.twig', $this->data);
    }

    /**
     * controller action for submiting either the add or edit of account Payment
     *
     * @return TWIG
     */
    public function accountPaymentAddSubmitAction()
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $userId = $sessionInfo['userId'];
        $parameters = $this->get('request')->request->all();
        $parameters['createdBy'] = $userId;
        $parameters['moduleId'] = $this->container->getParameter('MODULE_FINANCE');

        $result = $this->get('CorpoAccountPaymentServices')->setAccountPaymentAddEdit($parameters);        
        if($result['status'] == 'success'){
            $this->addSuccessNotification($this->translator->trans($result['msg']));
        }else{
            $this->addErrorNotification($this->translator->trans("Error adding account."));
        }

        if($result['id'] != 0){
            return $this->redirectToLangRoute($result['route'], array('id' => $result['id']));
        }else{
            return $this->redirectToLangRoute($result['route']);
        }
    }

    /**
     * controller action for deleting an account Payment
     *
     * @return TWIG
     */
    public function accountPaymentDeleteAction($id)
    {
        $parameters = array(
            'reservationId' => $id,
            'moduleId' => $this->container->getParameter('MODULE_FINANCE')
        );
        $success = $this->get('CorpoAccountPaymentServices')->deleteCorpoAccountPayment($id);
        $success = $this->get('CorpoAccountTransactionsServices')->deleteCorpoAccountTransactions($parameters);

        if($success){
            $this->addSuccessNotification($this->translator->trans('Successfully deleted account transaction.'));
        }else{
            $this->addErrorNotification($this->translator->trans("Error in deleting."));
        }
        return $this->redirectToLangRoute('_corpo_account_payment');
    }

    public function accountPaymentTotalsAction()
    {
        $parameters = $this->get('request')->request->all();

        if (!isset($parameters['accountId']) || !empty($parameters['accountId'])) {
            $sessionInfo             = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $parameters['accountId'] = $sessionInfo['accountId'];
        }

        if (isset($parameters['fromDate']) && !empty($parameters['fromDate'])) {
            $parameters['firstDay'] = $parameters['fromDate'];
        }

        if (isset($parameters['toDate']) && !empty($parameters['toDate'])) {
            $parameters['lastDay'] = $parameters['toDate'];
        }

        $currentBalance           = $this->get('CorpoAccountServices')->getCurrentBalance($parameters);
        $accountPrefferedCurrency = $this->get('CorpoAccountServices')->getAccountPreferredCurrency($parameters['accountId']);
        $listSum                  = $this->get('CorpoAccountPaymentServices')->getAccountPaymentTotals($parameters);

        $data                      = array();
        $data['preferredCurrency'] = $accountPrefferedCurrency;
        $data['amountFBCCurrency'] = $this->container->getParameter('FBC_CODE');
        $data['amountSBCCurrency'] = $this->container->getParameter('SBC_CODE');
        $data['dueAmount']         = $currentBalance['sumAccountAmount'];
        $data['totalFbc']          = $listSum['sumAmountFBC'];
        $data['totalSbc']          = $listSum['sumAmountSBC'];

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }
}
