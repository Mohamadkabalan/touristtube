<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CorporateBundle\Model\UserProfiles;
use Symfony\Component\HttpFoundation\JsonResponse;
use CorporateBundle\Model\Menu;
use CorporateBundle\Model\ProfilePermissions;

/**
 * Controller receiving actions related to the accounts
 */
class CorpoUserProfilesController extends CorpoAdminController
{

    /**
     * controller action for the list of user profiles
     *
     * @return TWIG
     */
    public function userProfilesAction()
    {
        $this->data['userProfiles'] = $this->get('CorpoUserProfilesServices')->getCorpoUserProfilesList();
        return $this->render('@Corporate/admin/userProfiles/userProfileList.twig', $this->data);
    }

    public function getDataTableAction()
	{
        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();

        $sql = $this->get('CorpoUserProfilesServices')->prepareUserProfilesDtQuery();
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => " GROUP BY up.id",
            'table' => 'corpo_user_profiles',
            'key' => 'id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $userProfiles = $dtService->runQuery($queryArr);
        
        return new JsonResponse($userProfiles);
	}

    /**
     * controller action for adding or  edditing a user profile
     *
     * @return TWIG
     */
    public function userProfilesAddEditAction($id)
    {
        $this->data['sectionData'] = array(0=>[]);
        $this->data['menuListArray'] = array();

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
            $menuListArray = array();
            $profileMenuList      = $this->get('CorpoUserProfilesServices')->getProfileMenuListById($id);
            if ($profileMenuList) {
                foreach ($profileMenuList as $val) {
                    $menuListArray[] = $val['menuId'];
                }
            }
            $this->data['menuListArray'] = $menuListArray;
            $this->data['sectionData'] = $this->get('CorpoUserProfilesServices')->getCorpoUserProfileById($id);
        }
        return $this->render('@Corporate/admin/userProfiles/userProfileEdit.twig', $this->data);
    }

    /**
     * controller action for submiting either the add or edit of user profile
     *
     * @return TWIG
     */
    public function userProfilesSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $parameters['updatedBy'] = $sessionInfo['userId'];
        $userProfileObj = UserProfiles::arrayToObject($parameters);
        
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoUserProfilesServices')->updateUserProfile($userProfileObj);
            $parameters['profileId'] = $parameters['id'];
            $menuObj =  ProfilePermissions::arrayToObject($parameters);
            $success = $this->get('CorpoUserProfilesServices')->addProfileMenus($menuObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Profile menus updated successfully."));
                return $this->redirectToLangRoute('_corpo_user_profiles_edit', array('id' => $parameters['id']));
            } else {
                return $this->redirectToLangRoute('_corpo_user_profiles');
            }
        } else {
            $profileId = $this->get('CorpoUserProfilesServices')->addUserProfile($userProfileObj);
            if($profileId) {
                $parameters['profileId'] = $profileId;
                $menuObj =  ProfilePermissions::arrayToObject($parameters);
                $success = $this->get('CorpoUserProfilesServices')->addProfileMenus($menuObj);
                if ($success) {
                    $this->addSuccessNotification($this->translator->trans("Profile menus added successfully."));
                    return $this->redirectToLangRoute('_corpo_user_profiles');
                } else {
                    $this->addErrorNotification($this->translator->trans("Error adding user profile menus."));
                    return $this->redirectToLangRoute('_corpo_user_profiles_add');
                }
            } else {
                $this->addErrorNotification($this->translator->trans("Error adding user profile."));
                return $this->redirectToLangRoute('_corpo_user_profiles_add');
            }

        }
    }

    /**
     * Check for user profile name using AJAX call
     *
     * @return JSON
     */
    public function checkUserProfileDuplicateAction(Request $request)
    {
        $request = Request::createFromGlobals();
        $name    = $request->request->get('name', '');
        $slug    = $request->request->get('slug', '');
        
        $result['success'] = true;
        
        if($name != "") {
            $params['name'] = $name;
        }
        if($slug != "") {
            $params['slug'] = $slug;
        }
        
        if ($this->get('CorpoUserProfilesServices')->checkDuplicate($params)) { 
            $result['success'] = false;
        }
        
        $res = new Response(json_encode($result));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for unpublishing a user profile
     *
     * @return TWIG
     */
    public function userProfileUnpublishAction($id)
    {
        $success = $this->get('CorpoUserProfilesServices')->publish($id, 0);
        return $this->redirectToLangRoute('_corpo_user_profiles');
    }

    /**
     * controller action for publishing a user profile
     *
     * @return TWIG
     */
    public function userProfilePublishAction($id)
    {
        $success = $this->get('CorpoUserProfilesServices')->publish($id, 1);
        return $this->redirectToLangRoute('_corpo_user_profiles');
    }

    /**
     * controller action for getting a list of user profiles
     *
     * @return TWIG
     */
    public function userProfileComboAction(Request $request)
    {
        $res = $this->get('CorpoUsersServices')->getUserProfileCombo($request);
        
        return $res;
    }
}