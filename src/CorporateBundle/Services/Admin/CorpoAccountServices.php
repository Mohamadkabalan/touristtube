<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CorporateBundle\Services\Admin\CorpoAdminServices;
use TTBundle\Services\UserServices;
use TTBundle\Services\CurrencyService;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\libraries\CombogridService;
use TTBundle\Utils\PDFUtils;
use TTBundle\Utils\TTRouteUtils;
use TTBundle\Model\Email;
use TTBundle\Services\EmailServices;
use FlightBundle\Services\FlightServices;

class CorpoAccountServices
{
    protected $utils;
    protected $em;
    protected $container;
    protected $CorpoAdminServices;
    protected $CurrencyService;
    protected $userServices;
    protected $templating;
    protected $pdfUtils;
    protected $routeUtils;
    protected $emailServices;
    protected $flightServices;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container, CorpoAdminServices $CorpoAdminServices,CurrencyService $CurrencyService, UserServices $userService, $templating, PDFUtils $pdfUtils, TTRouteUtils $routeUtils, EmailServices $emailServices, FlightServices $flightServices)
    {
        $this->utils              = $utils;
        $this->em                 = $em;
        $this->container          = $container;
        $this->CorpoAdminServices = $CorpoAdminServices;
        $this->CurrencyService    = $CurrencyService;
        $this->userServices       = $userService;
        $this->templating         = $templating;
        $this->pdfUtils           = $pdfUtils;
        $this->routeUtils         = $routeUtils;
        $this->emailServices      = $emailServices;
        $this->flightServices      = $flightServices;
    }

    /**
     * getting from the repository all accounts
     *
     * @return list
     */
    public function getCorpoAdminAccountList($slug)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountList($slug);
        return $accountList;
    }

    public function prepareAccountDtQuery($request)
    {
        return $this->em->getRepository('CorporateBundle:CorpoAccount')->prepareAccountDtQuery($request);
    }

    /**
     * getting from the repository an account
     *
     * @return list
     */
    public function getCorpoAdminAccount($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountById($id);
        return $account;
    }

    /**
     * adding an account
     *
     * @return list
     */
    public function addAccount($accountObj)
    {
        $accountId = $this->em->getRepository('CorporateBundle:CorpoAccount')->addAccount($accountObj);
        return $accountId;
    }

    public function getPPath($parentId)
    {
        $pPath = NULL;
        if(!empty($parentId)) {
            $pPath = $this->em->getRepository('CorporateBundle:CorpoAccount')->getPPath($parentId);
        }
        
        return $pPath;
    }

    public function updatePath($accountId, $path)
    {
        return $this->em->getRepository('CorporateBundle:CorpoAccount')->updatePath($accountId, $path);
    }

    /**
     * updating an account
     *
     * @return list
     */
    public function updateAccount($accountObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccount')->updateAccount($accountObj);
        return $addResult;
    }

    /**
     * deleting an account
     *
     * @return list
     */
    public function deleteCorpoAccount($id)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoAccount')->deleteAccount($id);
        return $result;
    }

    /**
     * getting from the repository payment type of a given account
     * @param accountId
     * @return list
     */
    public function getCorpoAccountPaymentType($accountId)
    {
        if (!isset($accountId)) {
            $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
            $accountId   = $sessionInfo['accountId'];
        }
        $paymentType         = array();
        $accountList         = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountById($accountId);

		if (!isset($accountList) || !$accountList)
			return array();

        $paymentType['id']   = $accountList['paymentTypeId'];
        $paymentType['name'] = $accountList['paymentTypeName'];
        $paymentType['code'] = $accountList['paymentTypeCode'];
        return $paymentType;
    }

    /**
     * getting from the repository the preferred currency
     * @param accountId
     * @return list
     */
    public function getAccountPreferredCurrency($accountId)
    {

        if(!$accountId){
            $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
            $accountId      = $sessionInfo['accountId'];
        }
        $preferredCurrency = '';
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountById($accountId);
        if ($accountList)
			$preferredCurrency = $accountList['al_preferredCurrency'];

        return $preferredCurrency;
    }

    /**
     * getting from the repository the limit budget
     * @param accountId
     * @return list
     */
    public function isAccountBudgetAllowed($parameters)
    {

        if(!isset($parameters['accountId'])){
            $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
            $parameters['accountId']      = $sessionInfo['accountId'];
        }
        $limitBudget = '';
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountById($parameters['accountId']);
        $limitBudget = $accountList['al_creditLimit'];
        $currencyCode = $accountList['al_currencyCode'];
        //
        $amountAccountFBC = $this->CurrencyService->exchangeAmount($limitBudget, $currencyCode, $this->container->getParameter('FBC_CODE'));
        $balParams = array("accountId" => $parameters["accountId"]);
        $currentBalances = $this->getCurrentBalance($balParams);
        $currentBalanceFBC = $currentBalances['sumAmountFBC'];
        //
        $amountFBC = 0;
        if( isset($parameters['amount']) && isset($parameters['currencyCode']) && $parameters['amount'] > 0)
            $amountFBC = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('FBC_CODE'));

        if($amountAccountFBC <= ($currentBalanceFBC + $amountFBC)){
            return false;
        }else{
           return true;
        }

    }

    /**
     * getting from the repository the current balance
     * @param accountId
     * @return list
     */
    public function getCurrentBalance($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $parameters['sessionAccountId']      = $sessionInfo['accountId'];
        if(empty($parameters['accountId'])){
            $parameters['accountId']      = $sessionInfo['accountId'];
        }
        $currentBalances = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionTotals($parameters);
        return $currentBalances;
    }

    /**
     * getting aged and due trx amounts; the amount that passed the given payment period by the account ( any transaction made has a creation day her we are getting the amount that has passed the payment period in the account)
     * @param array
     * @return list
     */
    public function getAgedTrxAmount($parameters)
    {
        if(!isset($parameters['accountId'])){
            $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
            $parameters['accountId']      = $sessionInfo['accountId'];
        }
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountById($parameters['accountId']);
        $paymentPeriod = intval($accountList['al_paymentPeriod']);
        $currentDate = new \DateTime("now");
        $date = $currentDate->modify( '-'.$paymentPeriod.' days' )->format('Y-m-d');
        $parameters['dueDate'] = $date;

        $currentBalances = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionTotals($parameters);

        return $currentBalances;
    }

    public function getAccountCombo(Request $request)
    {
        $excludedAccountsIds = $request->request->get("excludedAccountsIds");
        $request->request->set("excluded", $excludedAccountsIds);
        //
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $userId       = $sessionInfo['userId'];
        $accountSessionId = $sessionInfo['accountId'];
        $accessToSubAcc = $sessionInfo['allowAccessSubAccounts'];
        $userObj = $this->userServices->getUserDetails(array('id' => $userId));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];
        $addWhere = "";
        if($userRole != $this->container->getParameter('ROLE_SYSTEM')) {
            $addWhere .= " AND p.path LIKE '%,$accountSessionId,%'";
        }

        if(!$accessToSubAcc) {
            $addWhere .= " AND p.id = $accountSessionId";
        }

        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountCombo($tt_search_critiria_obj, $addWhere);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);

        return $res;
    }

    public function sendInvoice($transactionId)
    {
        $payment = $this->em->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);

        if(!$payment){
            return false;
        }

        $logo = $this->container->getParameter('emails')['header']['logo'];
        $immageURL = $this->container->getParameter('BASE_URL') . $this->routeUtils->generateMediaURL($logo);

        $userId = $payment->getUserId();
        $userArray = $this->userServices->getUserDetails(array('id' => $userId));

        $accountInfo = $this->em->getRepository('CorporateBundle:CorpoAccount')->getAccountById($userArray[0]['accountId']);
        $accountingEmail = $accountInfo['al_accountingEmail'];

        $params  = array();
        $params['accountName']  = $accountInfo['al_name'];
        $params['imageURL']     = $immageURL;
        $params['amount']       = $payment->getAmount();
        list($whole, $decimal)  = explode('.', $params['amount']);
        $params['wholeAmount']  = $whole;
        $params['decimalAmount'] = $decimal;
        $params['currency']     = $payment->getCurrency();

        $params['address']      = $accountInfo['al_address'];
        $params['city']         = $accountInfo['cityName'];
        $params['countryCode']  = $accountInfo['countryCode'];
        $params['countryName']  = $accountInfo['countryName'];
        $params['accountingEmail']  = $accountingEmail;
        $params['paymentDate']  = $payment->getCreationDate();
        
        $params['paymentReference']  = ($payment->getPaymentType() == 'cc') ? 'Credit Card' : 'On Account';

        $phone = ($accountInfo['al_phone1']?$accountInfo['al_phone1']:'');
        $phone .= (($accountInfo['al_phone2'])?($phone?', ':'').$accountInfo['al_phone2']:'');
        $phone .= (($accountInfo['al_mobile'])?($phone?', ':'').$accountInfo['al_mobile']:'');
        $params['phone'] = $phone;


        $myFlightDetails = $this->flightServices->myFlightDetails($payment, $transactionId);
        $params['passengers'] = $myFlightDetails['passengersArray'];
        $params['flightDetail'] = $myFlightDetails['flightSegments'];

        $invoicePrefix = $this->container->getParameter('invoice_reference_prefix');
        $invoiceSuffix = date('y');
        $params['invoiceNo'] = $invoicePrefix . $transactionId . ' / ' . $invoiceSuffix;

        $invoiceFileName = "invoice_" . $transactionId . "_" . time() . '.pdf';
        $invoiceFileName = str_replace(array('-', '/'), array('', '_'), $invoiceFileName);

        $bodyMessage = $this->templating->render('emails/email_payment_success.twig',['accountInfo' => $params]);
        $pdfMessage  = $this->templating->render('emails/invoice_payment.twig',['accountInfo' => $params]);
        $pdfData = $this->pdfUtils->htmlToPDF($pdfMessage, $invoiceFileName, 'S');

        $emailVars                 = array();
        $emailVars['message']      = $bodyMessage;
        $emailVars['to']           = $accountingEmail;
        $emailVars['subject']      = $this->container->getParameter('emails')['subject']['account_invoice'];
        $emailVars['from']         = $this->container->getParameter('emails')['email_sent_from'];
        $emailVars['data']         = $pdfData;
        $emailVars['dataFileName'] = $invoiceFileName;
        $emailVars['dataType']     = 'application/pdf';

        $emailObj = Email::arrayToObject($emailVars);
        return $this->emailServices->send($emailObj);
    }
}
