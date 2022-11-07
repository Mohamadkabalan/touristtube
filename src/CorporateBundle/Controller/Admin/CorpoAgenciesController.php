<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CorporateBundle\Model\Agencies;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the Departments
 */
class CorpoAgenciesController extends CorpoAdminController
{

    /**
     * controller action for the list of Agencies
     *
     * @return TWIG
     */
    public function agenciesAction()
    {
        $this->data['agenciesList'] = $this->get('CorpoAgenciesServices')->getCorpoAdminAgenciesList();
        return $this->render('@Corporate/admin/agencies/agenciesList.twig', $this->data);
    }

    public function getDataTableAction()
	{
        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();

        $sql = $this->get('CorpoAgenciesServices')->prepareAgenciesDtQuery();
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => NULL,
            'table' => 'corpo_agencies',
            'key' => 'ca.id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $agencies = $dtService->runQuery($queryArr);
        
        return new JsonResponse($agencies);
	}

    /**
     * controller action for adding or edditing a Agencies
     *
     * @return TWIG
     */
    public function agenciesAddEditAction($id)
    {
        $this->data['agency'] = array();
        if ($id) {
            $this->data['agency'] = $this->get('CorpoAgenciesServices')->getCorpoAdminAgency($id);
        }
        return $this->render('@Corporate/admin/agencies/agenciesEdit.twig', $this->data);
    }

    /**
     * controller action for deleting a Agencies
     *
     * @return TWIG
     */
    public function agenciesDeleteAction($id)
    {
        $success = $this->get('CorpoAgenciesServices')->deleteAgencies($id);
        return $this->redirectToLangRoute('_corpo_agencies');
    }

    /**
     * controller action for submiting either the add or edit of Agencies
     *
     * @return TWIG
     */
    public function agenciesAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        $agenciesObj = Agencies::arrayToObject($parameters);
        
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoAgenciesServices')->updateAgencies($agenciesObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Updated successfully."));
                return $this->redirectToLangRoute('_corpo_agencies_edit', array('id' => $parameters['id']));
            } else {
                return $this->redirectToLangRoute('_corpo_agencies');
            }
        } else {
            $success = $this->get('CorpoAgenciesServices')->addAgencies($agenciesObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Added successfully."));
                return $this->redirectToLangRoute('_corpo_agencies');
            } else {
                $this->addErrorNotification($this->translator->trans("Error adding agency."));
                return $this->redirectToLangRoute('_corpo_agencies_add');
            }
        }
    }

    /**
     * controller action for getting a list of Agencies
     *
     * @return TWIG
     */
    public function adminSearchAgenciesAction()
    {
        $request = Request::createFromGlobals();
        $term    = strtolower($request->query->get('q', ''));
        $limit   = 100;
        $data    = $this->get('CorpoAgenciesServices')->getCorpoAdminLikeAgencies($term, $limit);

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for getting a list of agencies
     *
     * @return TWIG
     */
    public function agencyComboAction(Request $request)
    {
        $res = $this->get('CorpoAgenciesServices')->getAgencyCombo($request);
        
        return $res;
    }
}