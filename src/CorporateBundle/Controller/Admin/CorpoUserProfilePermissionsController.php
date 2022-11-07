<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the accounts
 */
class CorpoUserProfilePermissionsController extends CorpoAdminController
{

    /**
     * controller action for the permission list use profiles
     *
     * @return TWIG
     */
    public function profilesPermissionsAction()
    {
        return $this->render('@Corporate/admin/profilePermissions/permissionsList.twig', $this->data);
    }

    public function getDataTableAction()
	{
        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();

        $sql = $this->get('CorpoProfilePermissionsServices')->prepareProfilesPermissionDtQuery();
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => NULL,
            'table' => 'corpo_user_profile_menus_permission',
            'key' => 'pp.id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $permissions = $dtService->runQuery($queryArr);
        
        return new JsonResponse($permissions);
	}
}