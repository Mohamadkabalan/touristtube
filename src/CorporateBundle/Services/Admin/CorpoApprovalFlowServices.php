<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;
use CorporateBundle\Services\Admin\CorpoAccountServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\CurrencyService;
use \Datetime;

class CorpoApprovalFlowServices
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
    }

    /**
     * getting from the repository all Approval Flows
     *
     * @return list
     */
    public function  getCorpoAdminApprovalFlowList($accountId)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if (!$accountId) {
            $accountId = $sessionInfo['accountId'];
        }
        $approvalFLowList = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getApprovalFlowList($accountId);
        return $approvalFLowList;
    }

    /**
     * getting from the repository an Approval Flow
     *
     * @return list
     */
    public function getCorpoAdminApprovalFlow($accountId, $userId)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if (!$accountId) {
            $accountId = $sessionInfo['accountId'];
        }
        if (!$userId) {
            $userId = $sessionInfo['userId'];
        }
        $account = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getApprovalFlowById($accountId, $userId);
        return $account;
    }

    /**
     * adding an Approval Flow
     *
     * @return list
     */
    public function addApprovalFlow($parameters)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if (!isset($parameters['accountId'])) {
            $parameters['accountId'] = $sessionInfo['accountId'];
        }
        if (!isset($parameters['userId']) && isset($parameters['userNameCode'])) {
            $parameters['userId'] = $parameters['userNameCode'];
        }

        $approvalFlowParent = array_key_exists('approvalParent', $parameters);

        $approvalFlowRoot = array_key_exists('approvalMain', $parameters);

        $approvalFlowUser = array_key_exists('approvalUser', $parameters);

        $approveAllUsers = array_key_exists('approvalForAllUsers', $parameters);

        $account = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getApprovalFlowById($parameters['accountId'], $parameters['userId']); 
        if ($account) {
            return false;
        }

        if (isset($parameters['parentId']) && $parameters['parentId'] != 0) {
        
            $parent = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getApprovalFlowById($parameters['accountId'], $parameters['parentId']);
            if(empty($parent)){
                $parentParameters['accountId'] =  $parameters['accountId'];
                $parentParameters['userId'] =  $parameters['parentId'];
                $parentParameters['parentId'] = 0;
                $parentApprovalFlowParent= 1;
                $parentApprovalFlowRoot= 0;
                $parentApprovalFlowUser= 1;
                $parentApproveAllUsers= 1;
                $addApprovalFlow = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->addApprovalFlow($parentParameters, $parentApprovalFlowParent, $parentApprovalFlowRoot, $parentApprovalFlowUser, $parentApproveAllUsers);
            }
        }
        // $parameters['parentId'] = null;
        $addResult = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->addApprovalFlow($parameters, $approvalFlowParent, $approvalFlowRoot, $approvalFlowUser, $approveAllUsers);
        return $addResult;
    }

    /**
     * deleting an Approval Flow
     *
     * @return list
     */
    public function deleteApprovalFlow($userId, $deleteChild)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        
        if (!isset($userId)) {
            $userId = $sessionInfo['userId'];
        }

        if($deleteChild == "true") {
            /*get path*/
            $pPath = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getUserPath($accountId, $userId);
            /**delete all user and its children */
            $result = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->deleteApprovalUser($pPath);
        } else {
            /**
             * get user info
             * child count, parentId, user path (path), user parent path (newPath)
             */
            $userInfo = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getUserInfo($accountId, $userId);
            $childCnt = (int) $userInfo['childCnt'];
            /**
             * has child user?
             */
            if($childCnt > 0) {
                /**
                 * if selected user is main parent, new parent is currently log-in user
                 */
                $parentId = $userInfo['parentId'];
                if(!$parentId) {
                    $parentId = $sessionInfo['userId'];
                }
                
                $delete = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->deleteApprovalFlow($accountId, $userId);
                /**
                 * if parent has no path, get path of first found user in the same account
                 */
                $newPath = $userInfo['newPath'];
                if(empty($newPath)) {
                    $accountRec = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getAccountPath($accountId);
                    $newPath = $accountRec['path'];
                    $parentId = $accountRec['userId'];
                }
                
                /**
                 * set new parent of level 1 child
                 */
                $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->setNewParent($parentId, $userId);
                
                $pPath = $userInfo['path'];
                $result = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->updateUserPath($pPath, $newPath);
            } else {
                $result = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->deleteApprovalFlow($accountId, $userId);
            }
        }
        
        return $result;
    }

    /**
     * updating an Approval Flow
     *
     * @return list
     */
    public function updateApprovalFlow($parameters)
    {
        if (array_key_exists('approvalParent', $parameters)) {
            $approvalFlowParent = 1;
        } else {
            $approvalFlowParent = 0;
        }
        if (array_key_exists('approvalMain', $parameters)) {
            $approvalFlowRoot = 1;
        } else {
            $approvalFlowRoot = 0;
        }
        if (array_key_exists('approvalUser', $parameters)) {
            $approvalFlowUser = 1;
        } else {
            $approvalFlowUser = 0;
        }
        if (array_key_exists('approvalForAllUsers', $parameters)) {
            $approveAllUsers = 1;
        } else {
            $approveAllUsers = 0;
        }
        $addResult = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->updateApprovalFlow($parameters, $approvalFlowParent, $approvalFlowRoot, $approvalFlowUser, $approveAllUsers);
        return $addResult;
    }
    

    /**
     * updating an Approval Flow that has a parent of a deleted user
     *
     * @return list
     */
    public function updateParentApprovalFlow($parameters)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->updateParentApprovalFlow($parameters);
        return $addResult;
    }

    /**
     * adding a pending request service to get an Approval Flow
     * @param  $parameters = array(
     *                               'requestId' => ??
     *                               'userId' => ??
     *                               'accountId' => ??
     *                               'reservationId' => ??
     *                               'moduleId' => ??
     *                               'currencyCode' => ??
     *                               'amount' => ??
     *                             )
     *         $status constant yaml variable
     * @return list
     */
    public function addPendingRequestServices($parameters, $status)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if (!isset($parameters['accountId'])) {
            $parameters['accountId'] = $sessionInfo['accountId'];
        }
        if (!isset($parameters['userId'])) {
            $parameters['userId'] = $sessionInfo['userId'];
        }
        if (!$status) {
            $statusInfo = $this->em->getRepository('CorporateBundle:CorpoApprovalFlowStatus')->getApprovalFlowStatus();
            $status     = $statusInfo['id'];
        }
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

        if (isset($parameters['reservationId']) && isset($parameters['moduleId'])) {
            $getPendingRequest = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->getPendingRequestDetailsId($parameters['reservationId'], $parameters['moduleId']);
        } else {
            return false;
        }

        if (!$getPendingRequest) {
            $addResult = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->addPendingRequestServices($parameters, $status);
            return $addResult;
        } else {
            return false;
        }
    }

    /**
     * checking if user have permission for approving
     * @param $userId
     * @param $accountId
     * @return boolean
     */
    public function userAllowedToApprove($userId, $accountId)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if (!isset($accountId)) {
            $accountId = $sessionInfo['accountId'];
        }
        if (!isset($userId)) {
            $userId = $sessionInfo['userId'];
        }
        $permision = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->userAllowedToApprove($userId, $accountId);
        return (isset($permision) && isset($permision['approvalFlowUser']) && $permision['approvalFlowUser']);
    }

    /**
     * get all status
     * @return boolean
     */
    public function getApprovalFlowAllStatus()
    {
        $status = $this->em->getRepository('CorporateBundle:CorpoApprovalFlowStatus')->getApprovalFlowAllStatus();
        if ($status) {
            return $status;
        } else {
            return false;
        }
    }

    /**
     * checking if the user can approve for the transaction userId
     * @param $transactionUserId
     * @param $userId
     * @param $accountId
     * @return list
     */
    public function allowedToApproveForUser($userId, $transactionUserId, $accountId)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        if (!isset($accountId)) {
            $accountId = $sessionInfo['accountId'];
        }
        if (!isset($userId)) {
            $userId = $sessionInfo['userId'];
        }
        $transactionUserIdInfo = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getApprovalFlowByUserId($transactionUserId, $accountId);
        if($transactionUserIdInfo){
            if ($transactionUserIdInfo['p_approvalFlowParent'] == 1 && $transactionUserIdInfo['p_parentId'] == $userId) {
                return true;
            } elseif ($transactionUserIdInfo['p_approvalFlowRoot'] == 1 && $transactionUserIdInfo['p_mainUserId'] == $userId) {
                return true;
            } elseif ($transactionUserIdInfo['p_otherUserId'] == $userId) {
                return true;
            } else {
                $UserIdInfo = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getApprovalFlowByUserId($userId, $accountId);
                if ($UserIdInfo['p_approveAllUsers'] == 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    /**
     * update status of the pending request
     * @param  $parameters = array(
     *                               'requestStatus' => ??
     *                               'reservationId' => ??
     *                               'moduleId' => ??
     *                               'id' => ??
     *                             )
     * @return boolean
     */
    public function updatePendingRequestServices($parameters)
    {
        $status = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->updatePendingRequestServices($parameters);
        if ($status) {
            return $status;
        } else {
            return false;
        }
    }

    /**
     * get the Approval Details Services
     *
     * @return list or false
     */
    public function getApprovalDetailsServices($parameters)
    {
        $status = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->getApprovalDetailsServices($parameters);
        if ($status) {
            return $status;
        } else {
            return false;
        }
    }

    /**
     * get the Approval Details Services
     *
     * @return list or false
     */
    public function getPendingRequest($parameters)
    {
        $pendingRequest = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->getPendingRequest($parameters);
        if ($pendingRequest) {
            return $pendingRequest;
        } else {
            return false;
        }
    }

    /**
     * get the Approval flow list
     *
     * @return list or false
     */
    public function getAllApprovalFlowList($params)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $params['sessionAccountId']      = $sessionInfo['accountId'];
        if(empty($params['accountId'])){
            $params['accountId']      = $sessionInfo['accountId'];
        }

        $approvalFlowList = $this->em->getRepository('CorporateBundle:CorpoApprovalFlow')->getALlApprovalFlowList($params);
        if ($approvalFlowList) {
            return $approvalFlowList;
        } else {
            return false;
        }
    }

    /**
     * get the summ of all amount of a given account
     * 
     * @return integer or false
     */
    public function getRequestDetailSumOfAmount($sessionAccountId, $status, $params)
    {
        $sumAmount = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->getRequestDetailSumOfAmount($sessionAccountId, $status, $params);
        if ($sumAmount) {
            return $sumAmount;
        } else {
            return false;
        }
    }

    /**
     * change the transaction to expiry
     * 
     * @return true or false
     */
    public function updateExpiredRecords($params)
    {
        $currentDate = date("Y-m-d H:i:s");
        $currentDateTime = strtotime($currentDate);
        $params['currentDate'] = $currentDateTime;
        $changeExpiry = $this->em->getRepository('CorporateBundle:CorpoRequestServicesDetails')->updateExpiredRecords($params);
    }

    public function allowApproveUser($accountId, $userId)
    {
        $userApproval = $this->getCorpoAdminApprovalFlow($accountId, $userId);
        if(count($userApproval) > 0 && ($userApproval['p_approvalFlowUser'] && $userApproval['p_approveAllUsers']) ) {
            $allowApprove = true;
        } else {
            $allowApprove = false;
        }
        
        return $allowApprove;
    }

    public function addUpdateApprovalFlow($parameters)
    {
        $userApproval = $this->getCorpoAdminApprovalFlow($parameters['accountId'], $parameters['userId']);
        if(isset($parameters['allowApprove'])) {
            $parameters['approvalUser'] = 1;
            $parameters['approvalForAllUsers'] = 1;
        }

        $parameters['parentId'] = '';
        if(count($userApproval) > 0) {
            $parameters['approvalFlowId'] = $userApproval['p_id'];
            $this->updateApprovalFlow($parameters);
        } else {
            $this->addApprovalFlow($parameters);
        }
    }
}
