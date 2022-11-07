<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;
use CorporateBundle\Services\Admin\CorpoAccountServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CorporateBundle\Model\Payment;
use CorporateBundle\Model\Transactions;
use TTBundle\Services\CurrencyService;
use Symfony\Component\HttpFoundation\Response;

class CorpoAccountPaymentServices
{
    protected $utils;
    protected $em;
    protected $CorpoAdminServices;
    protected $CorpoAccountServices;
    protected $CurrencyService;
    protected $container;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container, CorpoAdminServices $CorpoAdminServices, CurrencyService $CurrencyService, CorpoAccountServices $CorpoAccountServices)
    {
        $this->utils                = $utils;
        $this->em                   = $em;
        $this->CorpoAdminServices   = $CorpoAdminServices;
        $this->CurrencyService      = $CurrencyService;
        $this->CorpoAccountServices = $CorpoAccountServices;
        $this->container            = $container;

    }

    /**
     * getting from the repository all accounts
     *
     * @return list
     */
    public function getCorpoAdminAccountPaymentList($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $parameters['sessionAccountId']      = $sessionInfo['accountId'];
        if(empty($parameters['accountId'])){
            $parameters['accountId']      = $sessionInfo['accountId'];
        }
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->getAccountPaymentList($parameters);
        return $accountList;
    }

    public function preparePaymentDtQuery()
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $params['accountId'] = $sessionInfo['accountId'];
        //
        return $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->preparePaymentDtQuery($params);
    }

    /**
     * getting from the repository an account
     *
     * @return list
     */
    public function getCorpoAdminAccountPaymentbyId($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->getAccountPaymentById($id);
        return $account;
    }

    /**
     * adding an account
     *
     * @param $parameters array(
     *                           'accountId' => ??
     *                           'userId' => ??
     *                           'requestDetailId' => ??
     *                           'moduleId' => ??
     *                           'reservationId' => ??
     *                           'paymentId' => ??
     *                           'currencyCode' => ??
     *                           'amount' => ??
     *                           'description' => ??
     *                          )
     * @return list
     */
    public function addAccountPayment($parameters)
    {
        
        $preferredAccountCurrency = $this->CorpoAccountServices->getAccountPreferredCurrency($parameters['accountId']);
        if (isset($parameters['amount']) && isset($parameters['currencyCode']) && $preferredAccountCurrency) {
            $parameters['amountFBC'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('FBC_CODE'));
            $parameters['amountSBC'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('SBC_CODE'));
            $parameters['amountAccountCurrency'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $preferredAccountCurrency);
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->addAccountPayment($parameters);
        return $addResult;
    }

    /**
     * adding a account payment
     *
     * @param $paymentObj object
     * @return list
     */
    public function addAccountPaymentNew($paymentObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->addAccountPaymentNew($paymentObj);
        return $addResult;
    }

    public function setAccountPaymentAddEdit($parameters)
    {
        $Result = array();
        $Result['status'] = 'ok';
        $Result['msg'] ='';
        $Result['id'] = 0;
        $Result['route'] = '';
        
        $parameters['amount']     =($parameters['amount'])*-1;
        $preferredAccountCurrency = $this->CorpoAccountServices->getAccountPreferredCurrency($parameters['accountCode']);
        if (isset($parameters['amount']) && isset($parameters['currencyCode']) && $preferredAccountCurrency) {
            $parameters['amountFBC'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('FBC_CODE'));
            $parameters['amountSBC'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('SBC_CODE'));
            $parameters['amountAccountCurrency'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $preferredAccountCurrency);
        }
        $paymentObj = Payment::arrayToObject($parameters);

        if ($parameters['id'] != 0) {
            $Result['route'] = '_corpo_account_payment_edit';
            $success = $this->updateAccountPaymentNew($paymentObj);
            if ($success) {
                $parameters['reservationId'] = $parameters['id'];
                $parameters['requestDetailId'] = $parameters['id'];;
                $transactionsObj = Transactions::arrayToObject($parameters);
                $success2 = $this->container->get('CorpoAccountTransactionsServices')->updateAccountTransactionsNew($transactionsObj);
                $Result['id'] = $parameters['id'];
                $Result['msg'] = 'Account payment transaction updated successfully.';
                $Result['status'] = 'success';
            } else {
                $Result['id'] = $parameters['id'];
                $Result['msg'] = 'Error in updating transaction.';
                $Result['status'] = 'error';
            }
        } else {
            $Result['route'] = '_corpo_account_payment_add';
            $success = $this->addAccountPaymentNew($paymentObj);
            if ($success) {
                $parameters['reservationId'] = $success;
                $parameters['requestDetailId'] = $success;
                $transactionsObj = Transactions::arrayToObject($parameters);
                $success2 = $this->container->get('CorpoAccountTransactionsServices')->addAccountTransactionsNew($transactionsObj);
                if( !$success2 ){
                    $Result['status'] = 'error';
                    $Result['msg'] = 'Error adding account payment transaction.';
                }else{
                    $Result['status'] = 'success';
                    $Result['msg'] = 'Account payment transaction added successfully.';
                    $Result['route'] = '_corpo_account_payment';
                }
            } else {
                $Result['status'] = 'error';
                $Result['msg'] = $this->translator->trans('Error adding account payment.');
            }
        }
        return $Result;
    }

    /**
     * updating an account
     *
     * @return list
     */
    public function updateAccountPayment($parameters)
    {
        if (!isset($parameters['moduleId'])) {
            $parameters['moduleId'] = $this->container->getParameter('MODULE_FINANCE');
        }
        if(isset($parameters['accountCode']) && $parameters['accountCode'] != ''){
          $parameters['accountId'] = $parameters['accountCode'];
        }
        if(isset($parameters['userCode']) && $parameters['userCode'] != ''){
          $parameters['userId'] = $parameters['userCode'];
        }
        $preferredAccountCurrency = $this->CorpoAccountServices->getAccountPreferredCurrency($parameters['accountId']);
        if (isset($parameters['amount']) && isset($parameters['currencyCode']) && $preferredAccountCurrency) {
            $amountFBC = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('FBC_CODE'));
            $amountSBC = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('SBC_CODE'));
            $amountAccountCurrency = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $preferredAccountCurrency);
            if ($amountFBC) {
                $parameters['amountFBC'] = $amountFBC;
            }
            if ($amountSBC) {
                $parameters['amountSBC'] = $amountSBC;
            }
            if ($amountAccountCurrency) {
                $parameters['amountAccountCurrency'] = $amountAccountCurrency;
            }
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->updateAccountPayment($parameters);
        return $addResult;
    }

    /**
     * updating an account payment
     *
     * @return list
     */
    public function updateAccountPaymentNew($paymentObj)
    {
        $result = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->updateAccountPaymentNew($paymentObj);
        return $result;
    }

    /**
     * getting the sum of amounts in account Payment
     * 
     * @return list
     */
    public function getAccountPaymentTotals($parameters)
    {
        $sessionInfo    = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $parameters['sessionAccountId']      = $sessionInfo['accountId'];
        if(empty($parameters['accountId'])){
            $parameters['accountId']      = $sessionInfo['accountId'];
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->getAccountPaymentTotals($parameters);
        return $addResult;
    }

    /**
     * deleting an account
     *
     * @return list
     */
    public function deleteCorpoAccountPayment($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->deleteAccountPaymentById($id);
        return $addResult;
    }
    
    /**
     * This method will get Account Payment All Flight Info
     *
     * @return list
     */
    public function getAccountPaymentFlightAllInfo($id,$moduleId)
    {
        $flightInfo = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->getAccountPaymentFlightAllInfo($id,$moduleId);
        return $flightInfo;
    }

    /**
     * This method will get Account Payment All Hotel Info
     *
     * @return list
     */
    public function getAccountPaymentHotelAllInfo($id,$moduleId)
    {
        $hotelInfo = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->getAccountPaymentHotelAllInfo($id,$moduleId);
        return $hotelInfo;
    }

    /**
     * This method will get Account Payment All Deal Info
     *
     * @return list
     */
    public function getAccountPaymentDealAllInfo($id,$moduleId)
    {
        $dealInfo = $this->em->getRepository('CorporateBundle:CorpoAccountPayment')->getAccountPaymentDealAllInfo($id,$moduleId);
        return $dealInfo;
    }
}
