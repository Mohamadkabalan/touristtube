<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;
use CorporateBundle\Services\Admin\CorpoAccountServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\CurrencyService;
use CorporateBundle\Model\AccountTransaction;

class CorpoAccountTransactionsServices
{
    protected $utils;
    protected $em;
    protected $CorpoAdminServices;
    protected $CurrencyService;
    protected $CorpoAccountServices;
    protected $container;

    public function __construct(Utils $utils, EntityManager $em, ContainerInterface $container, CorpoAdminServices $CorpoAdminServices, CurrencyService $CurrencyService,
                                CorpoAccountServices $CorpoAccountServices)
    {
        $this->utils                = $utils;
        $this->em                   = $em;
        $this->CorpoAdminServices   = $CorpoAdminServices;
        $this->CurrencyService      = $CurrencyService;
        $this->CorpoAccountServices = $CorpoAccountServices;
        $this->container            = $container;
        $this->translator           = $this->container->get('translator');
    }

    /**
     * getting from the repository all accounts
     *
     * @return list
     */
    public function getCorpoAdminAccountTransactionsList($parameters)
    {
        $accountList = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionsList($parameters);
        return $accountList;
    }

    public function prepareTransactionsDtQuery()
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $params['accountId'] = $sessionInfo['accountId'];
        return $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->prepareTransactionsDtQuery($params);
    }

    /**
     * getting from the repository an account
     *
     * @return list
     */
    public function getCorpoAdminAccountTransactionsbyId($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionsById($id);
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
    public function addAccountTransactions($parameters)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId   = $sessionInfo['accountId'];
        $userId      = $sessionInfo['userId'];
        if (!isset($parameters['accountId'])) {
            $parameters['accountId'] = $accountId;
        }
        if (!isset($parameters['userId'])) {
            $parameters['userId'] = $userId;
        }
        $preferredAccountCurrency = $this->CorpoAccountServices->getAccountPreferredCurrency($parameters['accountId']);
        if (isset($parameters['amount']) && isset($parameters['currencyCode']) && $preferredAccountCurrency) {
            $parameters['amountFBC']             = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('FBC_CODE'));
            $parameters['amountSBC']             = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('SBC_CODE'));
            $parameters['amountAccountCurrency'] = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $preferredAccountCurrency);
        }

        if (!isset($parameters['requestDetailId']) && ( isset($parameters['reservationId']) && isset($parameters['moduleId']) )) {
            $request = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->getPendingRequestDetailsId($parameters['reservationId'], $parameters['moduleId']);

            $requestDetailId               = $request['p_id'];
            $parameters['requestDetailId'] = $requestDetailId;
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->addAccountTransactions($parameters);
        return $addResult;
    }

    public function addAccountTransactionsNew($transactionsObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->addAccountTransactionsNew($transactionsObj);
        return $addResult;
    }

    /**
     * updating an account
     *
     * @return list
     */
    public function updateAccountTransactions($parameters)
    {
        $preferredAccountCurrency = $this->CorpoAccountServices->getAccountPreferredCurrency($parameters['accountId']);
        if (isset($parameters['amount']) && isset($parameters['currencyCode']) && $preferredAccountCurrency) {
            $amountFBC             = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('FBC_CODE'));
            $amountSBC             = $this->CurrencyService->exchangeAmount($parameters['amount'], $parameters['currencyCode'], $this->container->getParameter('SBC_CODE'));
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
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->updateAccountTransactions($parameters);
        return $addResult;
    }

    /**
     * updating an account transaction
     *
     * @return list
     */
    public function updateAccountTransactionsNew($transactionsObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->updateAccountTransactionsNew($transactionsObj);
        return $addResult;
    }

    /**
     * This method refunds a whole transaction, a partial amount, or adds a cancellationFee to the account
     *
     * @return
     */
    public function refundAccountTransactions(AccountTransaction $accountTransaction)
    {
        $success = false;

        // Update transaction status to CORPO_APPROVAL_CANCELED(3) in corpo_request_services_details
        $parameters = array(
            'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_CANCELED'),
            'reservationId' => $accountTransaction->getReservationId(),
            'moduleId' => $accountTransaction->getModuleId()
        );
        $this->container->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);

        // Get the corresponding corpo_account_transactions record by reservation_id and moduleid
        $requestAccountTransaction = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionsByReservationId($accountTransaction->getReservationId(), $accountTransaction->getModuleId());

        if (!empty($requestAccountTransaction)) {
            // Add a new transaction to corpo_account_transactions
            $params = array(
                'accountId' => $requestAccountTransaction['al_accountId'],
                'userId' => $requestAccountTransaction['al_userId'],
                'requestDetailId' => $requestAccountTransaction['al_requestDetailId'],
                'reservationId' => $requestAccountTransaction['al_reservationId'],
                'moduleId' => $requestAccountTransaction['al_moduleId'],
            );

            // If amountToRefund is 0, add a new transaction to revert the whole transaction
            if ($accountTransaction->getAmountToRefund() == 0) {
                $params['currencyCode']          = $requestAccountTransaction['al_currencyCode'];
                $params['amount']                = floatval($requestAccountTransaction['al_amount']) * -1;
                $params['amountFBC']             = floatval($requestAccountTransaction['al_amountFBC']) * -1;
                $params['amountSBC']             = floatval($requestAccountTransaction['al_amountSBC']) * -1;
                $params['amountAccountCurrency'] = floatval($requestAccountTransaction['al_amountAccountCurrency']) * -1;
                $params['description']           = $this->translator->trans("Full refund for reservation #").$accountTransaction->getReservationId();

                $success = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->addAccountTransactions($params);
            }
            // If we have amountToRefund, add a new transaction to deduct that passed amount only from the account
            else {
                $params['currencyCode'] = $accountTransaction->getCurrency();
                $params['amount']       = floatval($accountTransaction->getAmountToRefund()) * -1;
                $params['description']  = $this->translator->trans("Partial refund for reservation #").$accountTransaction->getReservationId();

                $success = $this->addAccountTransactions($params);
            }

            // If we have cancellationFees passed, add a new transaction to add the cancellation fees to the account
            if ($accountTransaction->getcancellationFees() > 0) {
                $params['currencyCode'] = $accountTransaction->getCurrency();
                $params['amount']       = $accountTransaction->getcancellationFees();
                $params['description']  = $this->translator->trans("Cancellation fees for reservation #").$accountTransaction->getReservationId();

                $success = $this->addAccountTransactions($params);
            }
        }

        return $success;
    }

    /**
     * getting the sum of amounts in account transactions
     *
     * @return list
     */
    public function getAccountTransactionTotals($parameters)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $parameters['sessionAccountId'] = $sessionInfo['accountId'];
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionTotals($parameters);
        return $addResult;
    }

    /**
     * deleting an account
     *
     * @return list
     */
    public function deleteCorpoAccountTransactions($parameters)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->deleteAccountTransactionsById($parameters);
        return $addResult;
    }

    /**
     * This method will get Account Transaction All Flight Info
     *
     * @return list
     */
    public function getAccountTransactionFlightAllInfo($id, $moduleId)
    {
        $flightInfo = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionFlightAllInfo($id, $moduleId);
        return $flightInfo;
    }

    /**
     * This method will get Account Transaction All Hotel Info
     *
     * @return list
     */
    public function getAccountTransactionHotelAllInfo($id, $moduleId)
    {
        $hotelInfo = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionHotelAllInfo($id, $moduleId);
        return $hotelInfo;
    }

    /**
     * This method will get Account Transaction All Deal Info
     *
     * @return list
     */
    public function getAccountTransactionDealAllInfo($id, $moduleId)
    {
        $dealInfo = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionDealAllInfo($id, $moduleId);
        return $dealInfo;
    }

    /**
     * This method will get Account Transaction All Payment Info
     *
     * @return list
     */
    public function getCorpoAdminAccountPaymentAllInfo($id, $accountId, $moduleId)
    {
        $dealInfo = $this->em->getRepository('CorporateBundle:CorpoAccountTransactions')->getAccountTransactionPaymentAllInfo($id, $accountId, $moduleId);
        return $dealInfo;
    }
}
