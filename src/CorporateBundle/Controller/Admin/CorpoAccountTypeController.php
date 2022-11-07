<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CorporateBundle\Model\AccountType;
use CorporateBundle\Model\AccountTypeMenu;
use CorporateBundle\Model\Menu;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the accounts
 */
class CorpoAccountTypeController extends CorpoAdminController
{

    /**
     * controller action for the list of account types
     *
     * @return TWIG
     */
    public function accountTypeAction()
    {
        $this->data['accountTypeList'] = $this->get('CorpoAccountTypeServices')->getCorpoAdminAccountTypeList();
        return $this->render('@Corporate/admin/accountType/accountTypeList.twig', $this->data);
    }

    public function getDataTableAction()
	{
        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();

        $sql = $this->get('CorpoAccountTypeServices')->prepareAccountTypeDtQuery();
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => NULL,
            'table' => 'corpo_account_type',
            'key' => 'cat.id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $notification = $dtService->runQuery($queryArr);
        
        return new JsonResponse($notification);
	}

    /**
     * controller action for adding or  edditing an account type
     *
     * @return TWIG
     */
    public function accountTypeAddEditAction($id)
    {
        $this->data['menuListArray'] = array();
        $this->data['sectionData'] = array(0=>[]);

        $menuObj = Menu::arrayToObject(array());
        $menus = $this->get('CorpoAdminServices')->getMenus($menuObj);
        $menuList = [];
        /*arrange parent child menu  */
        foreach ($menus as $menu) {
            if(is_null($menu['m_parentId'])) {
                $menuList[$menu['m_id']][] = $menu;
            } else {
                $menuList[$menu['m_parentId']][] = $menu;
            }
        }
        $this->data['menuTree']             = $menuList;
        if ($id) {
            $accountTypeMenuListArray = array();
            $accountTypeMenuList      = $this->get('CorpoAccountTypeServices')->getAccountTypeMenuListById($id);
            if ($accountTypeMenuList) {
                foreach ($accountTypeMenuList as $val) {
                    $accountTypeMenuListArray[] = $val['menuId'];
                }
            }
            $this->data['menuListArray'] = $accountTypeMenuListArray;
            $this->data['sectionData']              = $this->get('CorpoAccountTypeServices')->getCorpoAdminAccountTypeById($id);
        }
        return $this->render('@Corporate/admin/accountType/accountTypeEdit.twig', $this->data);
    }

    /**
     * controller action for submiting either the add or edit of account type
     *
     * @return TWIG
     */
    public function accountTypeAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $parameters['createdBy'] = $sessionInfo['userId'];
        $acctTypeObj = AccountType::arrayToObject($parameters);

        if (!empty($parameters['id'])) {
            $success = $this->get('CorpoAccountTypeServices')->updateAccountType($acctTypeObj);
            $parameters['typeId'] = $parameters['id'];
            $menuObj =  AccountTypeMenu::arrayToObject($parameters);
            $success = $this->get('CorpoAccountTypeServices')->addAccountTypeMenus($menuObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Account type menus updated successfully."));
                return $this->redirectToLangRoute('_corpo_accountType_edit', array('id' => $parameters['id']));
            } else {
                return $this->redirectToLangRoute('_corpo_account_type');
            }
        } else {
            $acctTypeId = $this->get('CorpoAccountTypeServices')->addAccountType($acctTypeObj);
            if($acctTypeId) {
                $parameters['typeId'] = $acctTypeId;
                $menuObj =  AccountTypeMenu::arrayToObject($parameters);
                $success = $this->get('CorpoAccountTypeServices')->addAccountTypeMenus($menuObj);
                if ($success) {
                    $this->addSuccessNotification($this->translator->trans("Account type menus added successfully."));
                    return $this->redirectToLangRoute('_corpo_account_type');
                } else {
                    $this->addErrorNotification($this->translator->trans("Error adding account type menus."));
                    return $this->redirectToLangRoute('_corpo_accountType_add');
                }
            } else {
                $this->addErrorNotification($this->translator->trans("Error adding account type."));
                return $this->redirectToLangRoute('_corpo_accountType_add');
            }
        }
    }

    /**
     * controller action for deleting an account type
     *
     * @return TWIG
     */
    public function accountTypeDeleteAction($id)
    {
        $success = $this->get('CorpoAccountTypeServices')->deleteCorpoAccountType($id);
        return $this->redirectToLangRoute('_corpo_account_type');
    }

    /**
     * controller action for deactivating an account type
     *
     * @return TWIG
     */
    public function accountTypeDeactivateAction($id)
    {
        $success = $this->get('CorpoAccountTypeServices')->deactivateCorpoAccountType($id);
        return $this->redirectToLangRoute('_corpo_account_type');
    }

    /**
     * controller action for activating an account type
     *
     * @return TWIG
     */
    public function accountTypeActivateAction($id)
    {
        $success = $this->get('CorpoAccountTypeServices')->activateCorpoAccountType($id);
        return $this->redirectToLangRoute('_corpo_account_type');
    }

    /**
     * Check for account type name using AJAX call
     *
     * @return JSON
     */
    public function checkAccountTypeDuplicateAction(Request $request)
    {
        $request = Request::createFromGlobals();
        $name    = $request->request->get('accountTypeName', '');
        $slug    = $request->request->get('slug', '');
        
        $result['success'] = true;

        if($name != "") {
            $params['name'] = $name;
        }
        if($slug != "") {
            $params['slug'] = $slug;
        }
        
        if ($this->get('CorpoAccountTypeServices')->checkDuplicateAccountType($params)) { 
            $result['success'] = false;
        }
        
        $res = new Response(json_encode($result));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for getting a list of account types
     *
     * @return TWIG
     */
    public function accountTypeComboAction(Request $request)
    {
        $res = $this->get('CorpoAccountTypeServices')->getAccountTypeCombo($request);
        
        return $res;
    }
}