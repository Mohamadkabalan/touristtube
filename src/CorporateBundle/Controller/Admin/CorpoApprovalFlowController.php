<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller receiving actions related to the approval flow
 */
class CorpoApprovalFlowController extends CorpoAdminController
{

    /**
     * controller action for the list of approval Flows
     *
     * @return TWIG
     */
    public function approvalFlowAction()
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        $userId = $sessionInfo['userId'];
        
        $this->data['approvalFlowInfo'] = $this->get('CorpoApprovalFlowServices')->getCorpoAdminApprovalFlow($accountId, $userId);
        return $this->render('@Corporate/admin/approvalFlow/approvalFlowList.twig', $this->data);
    }

    /**
     * controller action for adding or edditing an approval Flow
     *
     * @return TWIG
     */
    public function approvalFlowAddEditAction($id, $type)
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        $this->data['type'] = $type;
        $this->data['accountId'] = $accountId;
        $this->data['approvalFlowInfo'] = array();
        $approvalFlow = $this->get('CorpoApprovalFlowServices')->getCorpoAdminApprovalFlow($accountId, $id);
        if($approvalFlow){
            $this->data['approvalFlowInfo'] = $approvalFlow;
        }
        return $this->render('@Corporate/admin/approvalFlow/approvalFlowAddEdit.twig', $this->data);
    }

    /**
     * controller action for deleting an approval Flow
     *
     * @return TWIG
     */
    public function approvalFlowDeleteAction()
    {
        $parameters = $this->get('request')->request->all();
        $userId = $parameters['userId'];
        $deleteChild = $parameters['deleteChild'];
        $success = $this->get('CorpoApprovalFlowServices')->deleteApprovalFlow($userId, $deleteChild);
        $res = new Response(json_encode($success));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for submiting either the add or edit of approval Flow
     *
     * @return TWIG
     */
    public function approvalFlowAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        if ($parameters['type'] == 2) {
            $success = $this->get('CorpoApprovalFlowServices')->updateApprovalFlow($parameters);
            if ($success) {
                return $this->redirectToLangRoute('_corpo_approval_flow');
            } else {
                return $this->redirectToLangRoute('_corpo_approval_flow_edit', array('id' => $parameters['userId']));
            }
        } else {
            $success = $this->get('CorpoApprovalFlowServices')->addApprovalFlow($parameters);
            if ($success) {
                return $this->redirectToLangRoute('_corpo_approval_flow');
            } else {
                return $this->redirectToLangRoute('_corpo_approval_flow_add');
            }
        }
    }

    /**
     * controller action to get the tree
     *
     * @return TWIG
     */
    public function getApprovalUsersTreeAction()
    {
        $sessionInfo    = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId      = $sessionInfo['accountId'];
        $tree = $this->get('CorpoApprovalFlowServices')->getCorpoAdminApprovalFlowList($accountId);
        $treeList = array();
        if($tree !== false){
            foreach($tree as $nodeTree){
                $nodeTreeList = array();
                $nodeTreeList['id'] = "".$nodeTree['ap_userId'];
                if($nodeTree['ap_parentId']){
                    $nodeTreeList['parent'] = "".$nodeTree['ap_parentId'];
                }else{
                    $nodeTreeList['parent'] = "#";
                }
                $nodeTreeList['text'] = $nodeTree['userName'];
                $treeList[] = $nodeTreeList;
            }
        }
        $res        = new Response(json_encode($treeList));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }
}