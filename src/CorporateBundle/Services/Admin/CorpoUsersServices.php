<?php

namespace CorporateBundle\Services\Admin;

use Doctrine\ORM\EntityManager;
use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\EmailServices;
use Symfony\Component\HttpFoundation\Request;
use TTBundle\Services\libraries\CombogridService;

class CorpoUsersServices
{
    protected $utils;
    protected $em;
    protected $CorpoAdminServices;
    protected $CorpoApprovalFlowServices;
    protected $templating;
    protected $emailServices;
    protected $container;
    protected $translator;

    public function __construct(Utils $utils, EntityManager $em, CorpoAdminServices $CorpoAdminServices, $templating, EmailServices $emailservices, ContainerInterface $container,
                                CorpoApprovalFlowServices $CorpoApprovalFlowServices)
    {
        $this->utils                     = $utils;
        $this->em                        = $em;
        $this->CorpoAdminServices        = $CorpoAdminServices;
        $this->CorpoApprovalFlowServices = $CorpoApprovalFlowServices;
        $this->templating                = $templating;
        $this->emailServices             = $emailservices;
        $this->container                 = $container;
        $this->translator                = $this->container->get('translator');
    }

    /**
     * getting from the repository all Users
     *
     * @return list
     */
    public function getCorpoAdminUsersList()
    {
        $usersList = $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->getUsersList();
        return $usersList;
    }

    /**
     * getting from the repository all user profiles
     *
     * @return list
     */
    public function getCorpoUserProfilesList()
    {
        $profileList = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->getUserProfilesList();
        return $profileList;
    }
    
    public function prepareUserDtQuery($request)
    {
        return $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->prepareUserDtQuery($request);
    }

    public function profileListUserDtQuery($request)
    {
        return $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->profileListUserDtQuery($request);
    }
    
    public function prepareCustomerTransactionsDtQuery($request)
    {
        $sessionInfo         = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $request['accountId'] = $sessionInfo['accountId'];
        $request['profileId'] = $sessionInfo['profileId'];
        $request['groupId'] = $sessionInfo['userGroupId'];
        $request['userId'] = $sessionInfo['userId'];
        $request['parentsIds'] = $this->getAllProfileParentIDs($request);
        return $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->prepareCustomerTransactionsDtQuery($request);
    }    

    /**
     * AS ADMIN: user can see everyone, no restrictions
     * AS Affiliates: All his child. (sales, company , agency and users)
     * AS Company: users under that company
     * As Agency: users under that agency
     * @params array (userId, profileId, groupId)
     * @return list
     */
    public function getAllProfileParentIDs($params)
    {
        $affiliates = $this->container->getParameter('AFFILIATES');
        $salesPerson = $this->container->getParameter('SALES_PERSON');

        $parentsIds = array();
        $parentsIds[] = $params['userId'];
        if($params['groupId'] != $this->container->getParameter('ROLE_SYSTEM')){
            if ($params['profileId'] == $affiliates){

                $salesPersonList = $this->em->getRepository('TTBundle:CmsUsers')->findBy(array(
                    'parentUserId' => $params['userId'], 
                    'published' => 1, 
                    'corpoUserProfileId' => $salesPerson));

                $salesChildren = [];
                $companyAgencyChildren = [];
                if($salesPersonList){
                    foreach($salesPersonList as $sales){
                        $salesChildren[] = $parentsIds[] =  $sales->getId();
                    }
                }

                if($salesChildren){
                    $companyOrAgencyList = $this->em->getRepository('TTBundle:CmsUsers')->findBy(array('parentUserId' => $salesChildren,'published' => 1));     
                     foreach($companyOrAgencyList as $companyOrAgency){
                        $companyAgencyChildren = $parentsIds[] =  $companyOrAgency->getId();
                    }
                }

            }

            if ($params['profileId'] == $salesPerson){
                $companyOrAgencyList = $this->em->getRepository('TTBundle:CmsUsers')->findBy(array('parentUserId' => $params['userId'], 'published' => 1));
                 foreach($companyOrAgencyList as $companyOrAgency){
                    $parentsIds[] =  $companyOrAgency->getId();
                }                   
            }
        }

        return $parentsIds;
    }
    /**
     * getting from the repository a User
     *
     * @return list
     */
    public function getCorpoAdminUser($id)
    {
        $account = $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->getUserById($id);
        return $account;
    }
    
    /**
     * adding a User
     *
     * @return list
     */
    public function addUsers($parameters)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->addUsers($parameters);
        return $addResult;
    }
    
    /**
     * deleting a User
     *
     * @return list
     */
    public function deleteUsers($id)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->deleteUsers($id);
        return $addResult;
    }
    
    /**
     * updating a User
     *
     * @return list
     */
    public function updateUsers($parameters)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CorpoAdminUsers')->updateUsers($parameters);
        return $addResult;
    }
    
    /**
     *
     * @return string[]
     */
    public function uploadFile($file, $id)
    {
        $parameters                      = array();
        $directory                       = $this->container->getParameter('CONFIG_SERVER_ROOT').$this->container->getParameter('USER_AVATAR_RELATIVE_PATH');
        $name                            = $id."_avatar.".$file->getClientOriginalExtension();
        $fileUpload                      = $file->move($directory, $name);
        $parameters['p_profile_picture'] = $name;
        $parameters['p_image_path']      = $directory;
        return $parameters;
    }
    
    public function updateCmsUsersPicture($parameters)
    {
        $addResult = $this->em->getRepository('CorporateBundle:CmsUsers')->updateProfilePicture($parameters);
        return $addResult;
    }

    /*
     *
     * This method sent user creation of accounts to main account
     *
     * @param $params - array of criteria
     *
     * @return user array or false if fail
     */
    public function sendUserCreationEmail($params = array())
    {
        $user = $this->container->get('CorpoAccountServices')->getCorpoAdminAccount($params['accountId']);

        $userFullName = $params['fname'].' '.$params['lname'];
        $corpoEmail    = $user['al_email'];

        /*
         * Send email here
         */
        $emailVars                     = array();
        $emailVars['to_email']         = $corpoEmail;
        $emailVars['displayTopHead']   = $userFullName;

        $subject         = $this->translator->trans("New User Account Created");
        $notifcationTwig = '@Corporate/email_templates/admin/main_account_email_notification.twig';

        $msg    = $this->templating->render($notifcationTwig, $emailVars);
        $status = $this->emailServices->addEmailData($corpoEmail, $msg, $subject, 'TouristTube.com', 0);
    }

    /**
     * allow user approval
     *
     * @return list
     */
    public function allow($id, $allowed)
    {
        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();

        $parameters  = array();

        $parameters['id'] = $id;
        $parameters['allowApprove'] = $allowed;
        if (!isset($parameters['accountId'])) {
            $parameters['accountId'] = $sessionInfo['accountId'];
        }

        $this->em->getRepository('CorporateBundle:CmsUsers')->unsetAllowApprove($parameters['accountId']);
        return $this->em->getRepository('TTBundle:CmsUsers')->modifyUser($parameters);
    }

    /**
     * getting from the repository all user profiles
     *
     * @return list
     */
    public function getUserProfileCombo(Request $request)
    {
        $userProfileLevel = $request->request->get("userProfileLevel");
        //
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        $tt_search_critiria_obj->addParam('userProfileLevel', $userProfileLevel);
        //
        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:CorpoUserProfiles')->getUserProfileCombo($tt_search_critiria_obj);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }

    /**
     * getting from the repository all published users 
     *
     * @return list
     */
    public function getUserCombo(Request $request)
    {
        $userProfileLevel = $request->request->get("userProfileLevel");
        //
        $tt_search_critiria_obj = CombogridService::prepareCriteria($request);
        $tt_search_critiria_obj->addParam('userProfileLevel', $userProfileLevel);

        $sessionInfo = $this->CorpoAdminServices->getLoggedInSessionInfo();
        $accountSessionId = $sessionInfo['accountId'];
        $accessToSubAccUsers = $sessionInfo['allowAccessSubAccountsUsers'];

        if($accessToSubAccUsers) {
            $moreWhere = " AND ca.path LIKE '%,$accountSessionId,%'";
        } else {
            $moreWhere = " AND p.corpoAccountId = $accountSessionId AND ca.path LIKE '%,$accountSessionId,%'";
        }

        //
        $combogrid_cats_res = $this->em->getRepository('CorporateBundle:CmsUsers')->getUserCombo($tt_search_critiria_obj, $moreWhere);
        $res = CombogridService::renderDropDownComboGrid($combogrid_cats_res["combogrid_cats"],$combogrid_cats_res["count"],'id','name',$request);
        //
        return $res;
    }

    /**
     * get account's approval user
     * 
     * @return User
     */
    public function getAllowApproveUserByAccId($accountId)
    {
        return $this->em->getRepository('TTBundle:CmsUsers')->findOneBy(array('corpoAccountId' => $accountId, 'allowApproval' => 1));
    }
}
