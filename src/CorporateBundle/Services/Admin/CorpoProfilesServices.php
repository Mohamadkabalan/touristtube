<?php

namespace CorporateBundle\Services\Admin;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use CorporateBundle\Services\Admin\CorpoAdminServices;

class CorpoProfilesServices
{
    protected $utils;
    protected $em;
    protected $CorpoAdminServices;

    public function __construct(Utils $utils, EntityManager $em, CorpoAdminServices $CorpoAdminServices)
    {
        $this->utils              = $utils;
        $this->em                 = $em;
        $this->CorpoAdminServices = $CorpoAdminServices;
    }

    /**
     * getting from the repository all Profiles
     *
     * @return list
     */
    public function getCorpoAdminProfilesList()
    {
        $sessionInfo  = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountId    = $sessionInfo['accountId'];
        $profilesList = $this->em->getRepository('CorporateBundle:CorpoProfiles')->getProfilesList($accountId);
        return $profilesList;
    }

    /**
     * getting from the repository a Profile
     *
     * @return list
     */
    public function getCorpoAdminProfile($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoProfiles')->getProfileById($id);
        return $account;
    }

    /**
     * adding a Profile
     *
     * @return list
     */
    public function addProfiles($profilesObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoProfiles')->addProfiles($profilesObj);
        return $addResult;
    }

    /**
     * deleting a Profile
     *
     * @return list
     */
    public function deleteProfiles($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoProfiles')->deleteProfiles($id);
        return $addResult;
    }

    /**
     * updating a Profile
     *
     * @return list
     */
    public function updateProfiles($profilesObj)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoProfiles')->updateProfiles($profilesObj);
        return $addResult;
    }
}