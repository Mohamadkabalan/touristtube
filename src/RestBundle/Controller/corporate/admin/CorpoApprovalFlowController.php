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

class CorpoApprovalFlowController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
    }

    /**
     * Method GET
     * GET SubUsers for an Account
     *
     * @QueryParam(name="userId")
     * @param $accountId
     * @param ParamFetcher $paramFetcher
     * @return response
     */
    public function getSubUsersAccountAction($accountId, ParamFetcher $paramFetcher)
    {
        if ($accountId == '') throw new HttpException(400, $this->translator->trans("Invalid account credentials. Please try again."));

        $post = $paramFetcher->all();
        $tree = $this->get('CorpoApprovalFlowServices')->getCorpoAdminApprovalFlowList($accountId);
        if ($tree !== false) {
            foreach ($tree as $nodeTree) {
                $nodeTreeList           = array();
                $nodeTreeList['userId'] = "".$nodeTree['ap_userId'];
                if ($nodeTree['ap_parentId']) {
                    $nodeTreeList['parentId']   = "".$nodeTree['ap_parentId'];
                    $nodeTreeList['parentName'] = $nodeTree['parentName'];
                } else {
                    $nodeTreeList['parentId']   = "#";
                    $nodeTreeList['parentName'] = "#";
                }
                $nodeTreeList['username']    = $nodeTree['userName'];
                $nodeTreeList['accountName'] = $nodeTree['accountName'];
                $nodeTreeList['permissions'] = array('approveAllUsers' => $nodeTreeList['p_approveAllUsers'],
                    'approvalFlowParent' => $nodeTreeList['p_approvalFlowParent'],
                    'approvalFlowRoot' => $nodeTreeList['p_approvalFlowRoot'],
                    'approvalFlowUser' => $nodeTreeList['p_approvalFlowUser']);

                $treeList[] = $nodeTreeList;
            }
        }
//        $results = array();
//        if ($approvalFlowInfo) {
//            $results['userId']         = $approvalFlowInfo['p_userId'];
//            $results['username']       = $approvalFlowInfo['userName'];
//            $results['parent']['name'] = $approvalFlowInfo['parentName'];
//            $results['parent']['id']   = $approvalFlowInfo['p_parentId'];
//            $results['accountName']    = $approvalFlowInfo['accountName'];
//            $results['permissions']    = array('approveAllUsers' => $approvalFlowInfo['p_approveAllUsers'],
//                'approvalFlowParent' => $approvalFlowInfo['p_approvalFlowParent'],
//                'approvalFlowRoot' => $approvalFlowInfo['p_approvalFlowRoot'],
//                'approvalFlowUser' => $approvalFlowInfo['p_approvalFlowUser']);
//        }

        $response = new Response(json_encode($treeList));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Update SubUsers for an Account
     *
     * @param $approvalFlowId
     * @return response
     */
    public function updateSubUsersAccountAction($approvalFlowId = 0)
    {
        if ($approvalFlowId == 0) throw new HttpException(400, $this->translator->trans("Invalid approval flow id credentials. Please try again."));

        // fech post json data
        $content = $this->request->getContent();
        $post    = json_decode($content, true);

        $params                   = array();
        $params['approvalFlowId'] = $approvalFlowId;
        if (isset($post['approvalByUserId'])) {
            $params['userId'] = $post['approvalByUserId'];
        }
        if (isset($post['approvalFromParent'])) {
            $params['approvalParent'] = $post['approvalFromParent'];
        }
        if (isset($post['approvalFromMain'])) {
            $params['approvalMain'] = $post['approvalFromMain'];
        }
        if (isset($post['approvalForAllUsers'])) {
            $params['approvalForAllUsers'] = $post['approvalForAllUsers'];
        }
        if (isset($post['selfApproval'])) {
            $params['approvalUser'] = $post['selfApproval'];
        }
        if (isset($post['parentId'])) {
            $params['parentId'] = $post['parentId'];
        }
        if (isset($post['accountId'])) {
            $params['accountId'] = $post['accountId'];
        }

        $success = $this->get('CorpoApprovalFlowServices')->updateApprovalFlow($params);
        $results = array();
        if ($success) {
            $results['status']  = 'success';
            $results['message'] = $this->translator->trans("Record updated successfully");
        } else {
            throw new HttpException(400, $this->translator->trans("Approval Flow Id is invalid"));
        }

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Add SubUsers for an Account
     *
     * @return response
     */
    public function addSubUsersAccountAction()
    {
        // specify required fields
        $requirements = array(
            'accountId',
            'parentId',
            'approvalByUserId',
            'approvalFromParent',
            'approvalFromMain',
            'selfApproval',
            'approvalForAllUsers'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        $params = array();
        if (isset($post['approvalByUserId'])) {
            $params['userId'] = $post['approvalByUserId'];
        }
        if (isset($post['approvalFromParent'])) {
            $params['approvalParent'] = $post['approvalFromParent'];
        }
        if (isset($post['approvalFromMain'])) {
            $params['approvalMain'] = $post['approvalFromMain'];
        }
        if (isset($post['approvalForAllUsers'])) {
            $params['approvalForAllUsers'] = $post['approvalForAllUsers'];
        }
        if (isset($post['selfApproval'])) {
            $params['approvalUser'] = $post['selfApproval'];
        }
        if (isset($post['parentId'])) {
            $params['parentId'] = $post['parentId'];
        }
        if (isset($post['accountId'])) {
            $params['accountId'] = $post['accountId'];
        }

        $success = $this->get('CorpoApprovalFlowServices')->addApprovalFlow($params);
        $results = array();
        if ($success) {
            $results['status']  = 'success';
            $results['message'] = $this->translator->trans("Record added successfully");
        } else {
            throw new HttpException(400, $this->translator->trans("Approval Flow Id is invalid"));
        }

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Reject
     *
     * @return $response
     */
    public function corporateApprovalRejectAction()
    {
        // specify required fields
        $requirements = array(
            array('name' => 'requestServicesDetailsId', 'required' => true, 'type' => 'integer'),
            array('name' => 'transactionUserId', 'required' => true, 'type' => 'integer')
        );

        // fech post json data
        $requestData = $this->fetchRequestData($requirements);
        extract($requestData);

        // retrieve user's id and accountId of the current session in case it's not on the post data
        $userRole  = 0;
        $accountId = 0;
        $userId    = $this->userGetID();

        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        if ($userObj) {
            $accountId = $userObj[0]['cu_corpoAccountId'];
            $userRole  = $userObj[0]['cu_cmsUserGroupId'];
        }

        $parameters = array(
            'accountId' => $accountId,
            'id' => $requestServicesDetailsId,
            'requestStatus' => $this->container->getParameter('CORPO_APPROVAL_REJECTED')
        );

        $checkApprove = $this->get('CorpoApprovalFlowServices')->allowedToApproveForUser($userId, $transactionUserId, $accountId);
        if ($checkApprove || $userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            $canceled = $this->get('CorpoApprovalFlowServices')->updatePendingRequestServices($parameters);
            return array("success" => true, "message" => $this->translator->trans("The reservation request was rejected."));
        } else {
            return array("success" => false, "message" => $this->translator->trans("The reservation request was not rejected."));
        }
    }
}
