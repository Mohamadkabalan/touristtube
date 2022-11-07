<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Controller receiving actions related to the Define Services
 */
class CorpoDefineServicesController extends CorpoAdminController
{

    /**
     * controller action for the list of define Service
     *
     * @return TWIG
     */
    public function defineServicesAction()
    {
        $this->data['defineServicesList'] = $this->get('CorpoDefineServicesServices')->getCorpoAdminDefineServicesList();
        return $this->render('@Corporate/admin/defineServices/defineServicesList.twig', $this->data);
    }

    /**
     * controller action for adding or edditing a define Service
     *
     * @return TWIG
     */
    public function defineServicesAddEditAction($id)
    {
        $this->data['defineService'] = array();
        if ($id) {
            $this->data['defineService'] = $this->get('CorpoDefineServicesServices')->getCorpoAdminDefineServices($id);
        }
        return $this->render('@Corporate/admin/defineServices/defineServicesEdit.twig', $this->data);
    }

    /**
     * controller action for deleting a define Service
     *
     * @return TWIG
     */
    public function defineServicesDeleteAction($id)
    {
        $success = $this->get('CorpoDefineServicesServices')->deleteDefineServices($id);
        return $this->redirectToLangRoute('_corpo_define_services');
    }

    /**
     * controller action for submiting either the add or edit of define Service
     *
     * @return TWIG
     */
    public function defineServicesAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoDefineServicesServices')->updateDefineServices($parameters);
            if ($success == 0) {
                return $this->redirectToLangRoute('_corpo_define_services');
            } else {
                return $this->redirectToLangRoute('_corpo_define_services_edit', array('id' => $parameters['id']));
            }
        } else {
            $success = $this->get('CorpoDefineServicesServices')->addDefineServices($parameters);
            if ($success) {
                return $this->redirectToLangRoute('_corpo_define_services');
            } else {
                return $this->redirectToLangRoute('_corpo_define_services_add');
            }
        }
    }
}