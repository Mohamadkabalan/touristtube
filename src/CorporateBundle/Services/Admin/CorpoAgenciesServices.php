<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\libraries\CombogridService;

class CorpoAgenciesServices
{
    protected $utils;
    protected $em;

    public function __construct(Utils $utils, EntityManager $em)
    {
        $this->utils     = $utils;
        $this->em        = $em;
    }

    /**
     * getting from the repository all Agencies
     *
     * @return list
     */
    public function getCorpoAdminAgenciesList()
    {
        $profilesList = $this->em->getRepository('CorporateBundle:CorpoAgencies')->getAgenciesList();
        return $profilesList;
    }

    public function prepareAgenciesDtQuery()
    {
        return $this->em->getRepository('CorporateBundle:CorpoAgencies')->prepareAgenciesDtQuery();
    }

    /**
     * getting from the repository all Agencies
     *
     * @return list
     */
    public function getCorpoAdminLikeAgencies($term, $limit)
    {
        $agenciesList = $this->em->getRepository('CorporateBundle:CorpoAgencies')->getCorpoAdminLikeAgencies($term, $limit);
        return $agenciesList;
    }

    /**
     * getting from the repository a Agencies
     *
     * @return list
     */
    public function getCorpoAdminAgency($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoAgencies')->getAgencyById($id);
        return $account;
    }

    /**
     * adding a Agencies
     *
     * @return list
     */
    public function addAgencies($agenciesObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAgencies')->addAgencies($agenciesObj);
        return $addResult;
    }

    /**
     * deleting a Agencies
     *
     * @return list
     */
    public function deleteAgencies($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAgencies')->deleteAgencies($id);
        return $addResult;
    }

    /**
     * updating a Agencies
     *
     * @return list
     */
    public function updateAgencies($agenciesObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAgencies')->updateAgencies($agenciesObj);
        return $addResult;
    }

    /**
     * getting from the repository all Agencies
     *
     * @return list
     */
    public function getAgencyCombo(Request $request)
    {
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        //
        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:CorpoAgencies')->getAgencyCombo($tt_search_critiria_obj);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }
}