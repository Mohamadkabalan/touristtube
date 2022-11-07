<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Controller receiving actions related to the request for services
 */
class CorpoRequestServicesController extends CorpoAdminController
{

    /**
     * controller action for the list of request Services
     * 
     * @return TWIG
     */
    public function requestServicesAction()
    {
        $this->data['requestServicesList'] = $this->get('CorpoRequestServices')->getCorpoAdminRequestServicesList();
        return $this->render('@Corporate/admin/requestServices/requestServicesList.twig', $this->data);
    }

    /**
     * controller action for adding or editing a request Service
     * 
     * @return TWIG
     */
    public function requestServicesAddEditAction($id)
    {
        $this->data['employeesList']       = $this->getEmployeesList();
        $this->data['servicesList']        = $this->getServicesList();
        $employeeIdListArray               = array();
        $ItemsIdsListArray                 = array();
        $this->data['employeeIdListArray'] = array();
        $this->data['itemsIdListArray']    = array();
        $this->data['requestService']      = array();
        if ($id) {
            $employeeIdList = $this->get('CorpoRequestServices')->getRequestEmployeeList($id);
            if ($employeeIdList) {
                foreach ($employeeIdList as $employeeIdList => $val) {
                    $employeeIdListArray[] = $val['employeeId'];
                }
            }
            $this->data['employeeIdListArray'] = $employeeIdListArray;
            $ItemsIdsListArray                 = array();
            $ItemsIdList                       = $this->get('CorpoRequestServices')->getRequestItemsList($id);
            if ($ItemsIdList) {
                foreach ($ItemsIdList as $ItemsIdList => $val) {
                    $ItemsIdsListArray[] = $val['serviceId'];
                }
            }
            $this->data['itemsIdListArray'] = $ItemsIdsListArray;
            $this->data['requestService']   = $this->get('CorpoRequestServices')->getCorpoAdminRequestServiceIdList($id);
        }else{
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($sessionInfo['accountId']);
            $requestService['accountId'] = $sessionInfo['accountId'];
            $requestService['accountName'] = $accountInfo['al_name'];
            $this->data['requestService'] = $requestService;
        }
        return $this->render('@Corporate/admin/requestServices/requestServicesEdit.twig', $this->data);
    }

    /**
     * controller action for deleting a request Service
     *
     * @return TWIG
     */
    public function requestServicesDeleteAction($id)
    {
        $success = $this->get('CorpoRequestServices')->deleteRequestServices($id);
        return $this->redirectToLangRoute('_corpo_request_services');
    }

    /**
     * controller action for submiting either the add or edit of request Service
     *
     * @return TWIG
     */
    public function requestServicesAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoRequestServices')->updateRequestServices($parameters);
            if ($success == 0) {
                return $this->redirectToLangRoute('_corpo_request_services');
            } else {
                return $this->redirectToLangRoute('_corpo_request_services_edit',array('id'=>$parameters['id']));
            }
        } else {
            $success = $this->get('CorpoRequestServices')->addRequestServices($parameters);
            if ($success) {
                return $this->redirectToLangRoute('_corpo_request_services');
            } else {
                return $this->redirectToLangRoute('_corpo_request_services_add');
            }
        }
    }
}