<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Controller receiving actions related to the Departments
 */
class CorpoDepartmentsController extends CorpoAdminController
{

    /**
     * controller action for the list of departments
     *
     * @return TWIG
     */
    public function departmentsAction()
    {
        $this->data['departmentsList'] = $this->get('CorpoDepartmentsServices')->getCorpoAdminDepartmentsList();
        return $this->render('@Corporate/admin/departments/departmentsList.twig', $this->data);
    }

    /**
     * controller action for adding or edditing a department
     *
     * @return TWIG
     */
    public function departmentsAddEditAction($id)
    {
        $this->data['department'] = array();
        if ($id) {
            $this->data['department'] = $this->get('CorpoDepartmentsServices')->getCorpoAdminDepartment($id);
        }else{
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($sessionInfo['accountId']);
            $department['accountId'] = $sessionInfo['accountId'];
            $department['accountName'] = $accountInfo['al_name'];
            $this->data['department'] = $department;
        }
        return $this->render('@Corporate/admin/departments/departmentsEdit.twig', $this->data);
    }

    /**
     * controller action for deleting a department
     *
     * @return TWIG
     */
    public function departmentsDeleteAction($id)
    {
        $success = $this->get('CorpoDepartmentsServices')->deleteDepartments($id);
        return $this->redirectToLangRoute('_corpo_departments');
    }

    /**
     * controller action for submiting either the add or edit of department
     *
     * @return TWIG
     */
    public function departmentsAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoDepartmentsServices')->updateDepartments($parameters);
            if ($success == 0) {
                return $this->redirectToLangRoute('_corpo_departments');
            } else {
                return $this->redirectToLangRoute('_corpo_departments_edit', array('id' => $parameters['id']));
            }
        } else {
            $success = $this->get('CorpoDepartmentsServices')->addDepartments($parameters);
            if ($success) {
                return $this->redirectToLangRoute('_corpo_departments');
            } else {
                return $this->redirectToLangRoute('_corpo_departments_add');
            }
        }
    }

    /**
     * controller action for getting a list of departments
     *
     * @return TWIG
     */
    public function adminSearchDepartmentAction()
    {
        $request = Request::createFromGlobals();
        $term    = strtolower($request->query->get('q', ''));
        $limit   = 100;
        $data    = $this->get('CorpoDepartmentsServices')->getCorpoAdminLikeDepartments($term, $limit);

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }
}