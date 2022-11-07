<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CorporateBundle\Model\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the accounts
 */
class CorpoAccountController extends CorpoAdminController
{

    /**
     * controller action for the list of accounts
     *
     * @return TWIG
     */
    public function accountAction($slug, Request $request)
    {
        $this->data['viewOnly'] = false;
        if($slug != "") {
            $this->data['slug'] = $slug;

            if($slug == 'company' || $slug == 'agency' || $slug == 'retail-agency') {
                $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
                $profileId = $sessionInfo['profileId'];
                $groupId = $sessionInfo['userGroupId'];
                if($profileId != $this->container->getParameter('SALES_PERSON') && $groupId != $this->container->getParameter('ROLE_SYSTEM')) {
                    $this->data['viewOnly'] = true;
                }
            }
        }
        $this->data['accountList'] = $this->get('CorpoAccountServices')->getCorpoAdminAccountList($slug);

        $url = $this->get('request')->getPathInfo();
        $menuObj = $this->get('CorpoAdminServices')->getMenusInfoByURL($url);

        if($menuObj){
            $sectionTitle = $this->translator->trans('List Of') . ' ' . $menuObj[0]->getName();
        }else{
            $sectionTitle = $this->translator->trans('List Of Accounts');
        }

        $this->data['sectionTitle'] = $sectionTitle;
        $salesUserId = $request->query->get('salesUserId', null);
        if(!empty($salesUserId)) {
            $this->data['salesUserId'] = $salesUserId;
            $this->data['viewOnly'] = true;
        }
        return $this->render('@Corporate/admin/account/accountList.twig', $this->data);
    }

    public function getDataTableAction()
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $userId       = $sessionInfo['userId'];
        
        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();
        $request['userId'] = $userId;

        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];
        $request['userRole'] = $userRole;
        $request['systemRole'] = $this->container->getParameter('ROLE_SYSTEM');

        $sql = $this->get('CorpoAccountServices')->prepareAccountDtQuery($request);
        
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => $sql->where . ' AND ca.is_active = 1 GROUP BY ca.id',
            'table' => 'corpo_account',
            'key' => 'ca.id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $account = $dtService->runQuery($queryArr);
        
        return new JsonResponse($account);
    }

    /**
     * controller action for adding or  edditing an account
     *
     * @return TWIG
     */
    public function accountAddEditAction($id, $slug)
    {
        $this->data['account']              = array();
        $this->data['accountTypeList']      = $this->get('CorpoAccountTypeServices')->getCorpoAdminAccountTypeActiveList();
        $this->data['paymentList']          = $this->get('CorpoAdminServices')->getAccountPaymentList();
        $this->data['slug'] = $slug;
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $userId       = $sessionInfo['userId'];
        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $userId));
        $accountTypeId = $userObj[0]['acctTypeId'];
        $userRole = $userObj[0]['cu_cmsUserGroupId'];
        if($userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            $showAccTtype = true;
        } else {
            $showAccTtype = false;
        }
        $this->data['showAccType'] = $showAccTtype;

        if ($id) {
            $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($id);
            $this->data['account'] = $accountInfo;
            $this->data['pageTitle'] = $this->translator->trans('edit');
            $this->data['action'] = 'edit';
        }else{
            $this->data['pageTitle'] = $this->translator->trans('add');
            $this->data['action'] = 'add';
        }
        
        $this->data['userAccTypeId'] = $accountTypeId;

        if($slug != "") {
            $menuObj = $this->get('CorpoAdminServices')->getMenusPathBySlug($slug);
            $sectionTitle = $this->translator->trans('List Of') . ' ' . $menuObj->getName();
        }else{
            $sectionTitle = $this->translator->trans('List Of Accounts');
        }
        $this->data['sectionTitle'] =  $sectionTitle;

        return $this->render('@Corporate/admin/account/accountEdit.twig', $this->data);
    }

    /**
     * controller action for submiting either the add or edit of account
     *
     * @return TWIG
     */
    public function accountAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        $slug = $parameters['slug'];

        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $parameters['userId'] = $sessionInfo['userId'];
        $accountObj = Account::arrayToObject($parameters);

        if ($parameters['id'] != 0) {
            $result = $this->get('CorpoAccountServices')->updateAccount($accountObj);
            $path = $this->getAccPath($accountObj->getParentId(), $parameters['id']);
            $success = $this->get('CorpoAccountServices')->updatePath($parameters['id'], $path);

            if ($result) {
                $this->addSuccessNotification($this->translator->trans("Updated successfully."));
                if($slug){
                    return $this->redirectToLangRoute('_corpo_account_edit_withslug', array('id' => $parameters['id'], 'slug' => $slug));
                }else{
                    return $this->redirectToLangRoute('_corpo_account_edit', array('id' => $parameters['id']));
                }
            } else {
                return $this->redirectToLangRoute('_corpo_accountslist', array('slug' => $slug));
            }
        } else {
            $accountId = $this->get('CorpoAccountServices')->addAccount($accountObj);
            $path = $this->getAccPath($accountObj->getParentId(), $accountId);
            $success = $this->get('CorpoAccountServices')->updatePath($accountId, $path);

            if ($accountId) {
                $this->addSuccessNotification($this->translator->trans("Added successfully."));
                if($slug){
                    return $this->redirectToLangRoute('_corpo_accountslist', ['slug' => $slug]);    
                }else{
                    return $this->redirectToLangRoute('_corpo_account');
                }
            } else {
                $this->addErrorNotification($this->translator->trans("Error adding account."));
                return $this->redirectToLangRoute('_corpo_account_add');
            }
        }
    }

    public function getAccPath($parentId, $accountId)
    {
        $pPath = $this->get('CorpoAccountServices')->getPPath($parentId);
        if(!empty($pPath) && !empty($pPath['path'])) {
            $path = $pPath['path'] . $accountId . ",";
        } else {
            $path = "," . $accountId . ",";
        }
        return $path;
    }

    /**
     * controller action for deleting an account
     *
     * @return TWIG
     */
    public function accountDeleteAction(Request $request, $id)
    {
        $success = $this->get('CorpoAccountServices')->deleteCorpoAccount($id);
        if(!empty($success)) {
            $this->addSuccessNotification("Successfully suspended account.");
        } else {
            $this->addErrorNotification("Error suspending account.");
        }
        
        $getData = $request->query->all();
        if(!empty($getData) && isset($getData['slug'])) {
            $slug = $getData['slug'];
            return $this->redirectToLangRoute('_corpo_accountslist', array('slug' => $slug));
        } else {
            return $this->redirectToLangRoute('_corpo_account');
        }
    }
    
    /**
     * controller action for getting a list of accounts
     *
     * @return TWIG
     */
    public function accountComboAction(Request $request)
    {
        $res = $this->get('CorpoAccountServices')->getAccountCombo($request);
        
        return $res;
    }
}
