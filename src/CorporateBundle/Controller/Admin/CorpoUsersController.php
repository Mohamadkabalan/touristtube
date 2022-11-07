<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use CorporateBundle\Model\Account;

/**
 * Controller receiving actions related to the Users
 */
class CorpoUsersController extends CorpoAdminController
{

    /**
     * controller action for the list of users
     *
     * @return TWIG
     */
    public function usersAction($slug, Request $request)
    {
        $title = 'Users';
        $this->data['slug'] = '';
        if($slug != "") {
            $this->data['slug'] = $slug;
            $userProfile = $this->get('CorpoUserProfilesServices')->getCorpoProfileBySlug($slug);
            $title .= ' - ' . $userProfile['up_sectionTitle'];
        } else {
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $profileId = $sessionInfo['profileId'];
            if($profileId == $this->container->getParameter('COMPANY') || $profileId == $this->container->getParameter('AGENCY') || $profileId == $this->container->getParameter('RETAIL_AGENCY')) {
                $title .= ' - ' . $sessionInfo['profileName'];
                $this->data['slug'] = 'users';
            }
        }
        $this->data['sectionTitle'] = $title;
        $affiliateUserId = $request->query->get('affiliateUserId', null);
        if(!empty($affiliateUserId)) {
            $this->data['affiliateUserId'] = $affiliateUserId;
        }
        return $this->render('@Corporate/admin/users/usersList.twig', $this->data);
    }

    public function getDataTableAction(Request $request)
	{
        $sessionInfo      = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountSessionId = $sessionInfo['accountId'];
        $accessToSubAccUsers = $sessionInfo['allowAccessSubAccountsUsers'];

        $dtService = $this->get('CorpoDatatable');
        $request = $request->request->all();
        $request['accessToSubAccUsers'] = $accessToSubAccUsers;
        $request['accountSessionId'] = $accountSessionId;
        $request['userId'] = $sessionInfo['userId'];
        $request['profileId'] = $sessionInfo['profileId'];
        $request['groupId'] = $sessionInfo['userGroupId'];

        $sql = $this->get('CorpoUsersServices')->prepareUserDtQuery($request);
        
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => $sql->where . ' AND cu.published != -2 GROUP BY cu.id',
            'table' => 'cms_users',
            'key' => 'cu.id',
            'columns' => NULL,
        );

        $queryArr = $dtService->buildQuery($params);
        $users = $dtService->runQuery($queryArr);

        return new JsonResponse($users);
	}

    public function viewProfileUserListAction($slug){
        $request = Request::createFromGlobals();
        $userId = $request->query->get('userId');

        $title = 'List of Users';
        if($slug != "") {
            $this->data['slug'] = $slug;
            $userProfile = $this->get('CorpoUserProfilesServices')->getCorpoProfileBySlug($slug);
            $title .= ' - ' . $userProfile['up_sectionTitle'];
        }

        $this->data['sectionTitle'] = $title;
        $this->data['parentId'] = $userId;
        return $this->render('@Corporate/admin/users/userListPerProfile.twig', $this->data);
    }

    public function profileListdatatableAction(){
        $sessionInfo      = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountSessionId = $sessionInfo['accountId'];
        $accessToSubAccUsers = $sessionInfo['allowAccessSubAccountsUsers'];

        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();

        $sql = $this->get('CorpoUsersServices')->profileListUserDtQuery($request);
        
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => $sql->where . ' AND cu.published != -2 GROUP BY cu.id',
            'table' => 'cms_users',
            'key' => 'cu.id',
            'columns' => NULL,
        );

        $queryArr = $dtService->buildQuery($params);
        $users = $dtService->runQuery($queryArr);

        return new JsonResponse($users); 
    }

    /**
     * controller action for edditing a user
     *
     * @return TWIG
     */
    public function usersAddEditAction($id, $slug='')
    {
        $params              = array();
        $n_chars = 8;
        $days = array();
        $months = array();
        $years = array();
        $days = range(1, 31);
        $years = range(date('Y')-18,1930);
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $months[] = $month;
        }
        $this->data['slug'] = $slug;
        $this->data['days'] = $days;
        $this->data['months'] = $months;
        $this->data['years'] = $years;
        $this->data['account'] = $this->data['user']  = array();
        $this->data['ROLE_CORPORATE']  = $this->container->getParameter('ROLE_CORPORATE');
        $this->data['paymentList']  = $this->get('CorpoAdminServices')->getAccountPaymentList();

        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        $userId = $sessionInfo['userId'];
        $userRole = $sessionInfo['userGroupId'];
        $userProfileLevel = $sessionInfo['profileLevel'];
        $this->data['userProfileLevel'] = $userProfileLevel;
        $params = array(
            'userRole' => $userRole,
            'ROLE_CORPORATE' => $this->container->getParameter('ROLE_CORPORATE'),
            'ROLE_ADMIN' => $this->container->getParameter('ROLE_ADMIN'),
            'ROLE_SYSTEM' => $this->container->getParameter('ROLE_SYSTEM'),
            'ROLE_USER' => $this->container->getParameter('ROLE_USER'),
            'ROLE_API' => $this->container->getParameter('ROLE_API')
        );
        $roles               = $this->get('UserServices')->getUserRoles($params);
        $passwordGenrated = $this->get('app.utils')->randomString($n_chars);
        $this->data['passwordGenrated'] = $passwordGenrated;
        $this->data['roles'] = $roles;


        if($slug == 'company' || $slug == 'agency' || $slug == 'retail-agency'){
            $params['accountId'] = $id;
        }else{
            $params['id'] = $id;
        }

        if ($id) {
            $user               = $this->get('UserServices')->getUserDetails($params);
            $userId = $user[0]['cu_id'];
            $accountId = $user[0]['accountId'];
            
            $userBOD = $user[0]['cu_yourbday'];
            if($user[0]['cu_yourbday'] != ''){
                $user[0]['cu_yourbday'] = $user[0]['cu_yourbday']->format('Y-m-d');
            }
            $this->data['user'] = $user[0];
            if ($slug == 'company' || $slug == 'agency' || $slug == 'retail-agency'){
                $this->showAccountDetails($id);
            }
        }
        
        $profileId = $sessionInfo['profileId'];
        if($userRole != $this->container->getParameter('ROLE_SYSTEM')) {
            $showAcc = false;
            switch($profileId) {
                case $this->container->getParameter('AFFILIATES'):
                    $profileId = $this->container->getParameter('SALES_PERSON');
                    $profileName = 'Sales Persons';
                    break;
                case $this->container->getParameter('COMPANY') || $this->container->getParameter('AGENCY') || $this->container->getParameter('RETAIL_AGENCY'):
                    if($slug == 'company'){
                        $profileId = $this->container->getParameter('COMPANY');
                        $profileName = 'Company';
                        $showAcc = true;
                    }elseif($slug == 'agency'){
                        $profileId = $this->container->getParameter('AGENCY');
                        $profileName = 'Agency';  
                        $showAcc = true;
                    }
                    elseif($slug == 'retail-agency'){
                        $profileId = $this->container->getParameter('RETAIL_AGENCY');
                        $profileName = 'Retail Agency'; 
                        $showAcc = true; 
                    }else{
                        $profileId = $this->container->getParameter('USERS');
                        $profileName = 'Users';      
                    }
                    break;
            }
        } else {
            $showAcc = false;
            if(!$id) {
                switch($slug) {
                    case 'affiliate':
                        $profileId = $this->container->getParameter('AFFILIATES');
                        $profileName = 'Affiliates';
                        break;
                    case 'sales':
                        $profileId = $this->container->getParameter('SALES_PERSON');
                        $profileName = 'Sales Persons';
                        break;
                    case 'company':
                        $profileId = $this->container->getParameter('COMPANY');
                        $profileName = 'Company';
                        $showAcc = true;
                        break;
                    case 'agency':
                        $profileId = $this->container->getParameter('AGENCY');
                        $profileName = 'Agency';
                        $showAcc = true;
                        break;
                    case 'retail-agency':
                        $profileId = $this->container->getParameter('RETAIL_AGENCY');
                        $profileName = 'Retail Agency';
                        $showAcc = true;
                        break;
                    default:
                        $profileId = $this->container->getParameter('USERS');
                        $profileName = 'Users';
                        break;
                }
            } else {
                $profileId = $user[0]['profileId'];
                $profileName = $user[0]['profileName'];
            }
        }

        $this->data['accountId'] = $this->container->getParameter('TOURISTTUBE_ACCOUNT');
        $this->data['accountName'] = 'Tourist Tube';
        $this->data['profileId'] = $profileId;
        $this->data['profileName'] = $profileName;
        $this->data['userRole'] = $userRole;
        $this->data['systemRole'] = $this->container->getParameter('ROLE_SYSTEM');
        $this->data['showAcc'] = $showAcc;
        if($sessionInfo['profileId'] == $this->container->getParameter('COMPANY') || $sessionInfo['profileId'] == $this->container->getParameter('AGENCY') || $sessionInfo['profileId'] == $this->container->getParameter('RETAIL_AGENCY')) {
            $allowApprove = $this->get('CorpoUsersServices')->getAllowApproveUserByAccId($accountId);
            $this->data['allowApprove'] = false;
            if($allowApprove && $allowApprove->getId() == $id) {
                $this->data['allowApprove'] = true;
            }
        }

        return $this->render('@Corporate/admin/users/usersEdit.twig', $this->data);
    }

    public function showAccountDetails($id)
    {        
        $user   = $this->get('UserServices')->getUserDetails(array('accountId' => $id));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];
        $accountTypeId = $userObj[0]['acctTypeId'];
        if($userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            $showAccTtype = true;
        } else {
            $showAccTtype = false;
        }
        $this->data['showAccType'] = $showAccTtype;

        if ($id) {
            $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($id);
            $this->data['account'] = $accountInfo;
            $this->data['pageTitle'] = $this->translator->trans('edit');
            $this->data['action'] = 'edit';
        }else{
            $this->data['pageTitle'] = $this->translator->trans('add');
            $this->data['action'] = 'add';
        }
        
        $this->data['userAccTypeId'] = $accountTypeId;
        $this->data['accountTypeList'] = $this->get('CorpoAccountTypeServices')->getCorpoAdminAccountTypeActiveList();
    }

    /**
     * controller action for deleting a user
     *
     * @return TWIG
     */
    public function usersDeleteAction($id, $slug = '')
    {
        $success = $this->get('UserServices')->deleteUser($id);
        $userObj = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));
        $accountId = $userObj[0]['cu_corpoAccountId'];
        $checkParent = $this->get('CorpoApprovalFlowServices')->getCorpoAdminApprovalFlow($accountId,$id);
        if($checkParent){
            $parentId = $checkParent['p_parentId'];

            $parameters['parentId'] = $parentId;
            $parameters['id'] = $id;

            $updateParentId = $this->get('CorpoApprovalFlowServices')->updateParentApprovalFlow($parameters);
        }

        if($success) {
            $this->addSuccessNotification($this->translator->trans("User has been suspended successfully."));
        } else {
            $this->addErrorNotification($this->translator->trans("Error in suspending user. Please try again."));
        }

        if($slug){
            return $this->redirectToLangRoute('_corporate_userslist', array('slug' => $slug));
        }else{
            return $this->redirectToLangRoute('_corpo_users');    
        }
        
    }

    /**
     * Check for user duplicate email and user name using AJAX call
     *
     * @return json response
     */
    public function checkDuplicateUserAction()
    {
        $request    = Request::createFromGlobals();
        $yourEmail      = $request->request->get('yourEmail', '');
        $userId         = $request->request->get('userId', '');
        $yourUserName   = $request->request->get('yourUserName', '');

        $error  = array();
        $result = array();
        $corporateError = 0;

        if ($yourEmail) {
            if ($this->get('UserServices')->checkDuplicateUserEmail($userId, $yourEmail)) { 
                $email = $yourEmail;
                $userByEmail = $this->get('UserServices')->getUserByEmailYourUserName($email);
                $userCorporate = $userByEmail['cc_corpoAccountId'];
                if(!$userCorporate || $userCorporate <= 0){
                    $corporateError = 1;
                    $error[] = $this->translator->trans("This email is already found and it's not corporate,do you wish to change it to coporate");
                    $userName = $userByEmail['cc_yourusername'];
                    $userEmail = $userByEmail['cc_youremail'];
                    $userId = $userByEmail['cc_id'];
                }else{
                    $error[] = $this->translator->trans('Duplicate Email.');
                }
            }
            
        }
        if ($yourUserName) {
            if ($this->get('UserServices')->checkDuplicateUserName($userId, $yourUserName)) {
                $error[] = $this->translator->trans('Duplicate User Name.');
            }
        }
        if (!empty($error)) {
            $result['success'] = false;
            $result['message'] = $error;
            if($corporateError == 1){
                $result['corporate'] = 1;
                $result['userName'] = $userName;
                $result['userEmail'] = $userEmail;
                $result['userId'] = $userId;
            }
        } else {
            $result['success'] = true;
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Controller action for submiting either the add or edit of user
     *
     * @return TWIG
     */
    public function usersAddSubmitAction()
    {
        $parameters  = $this->get('request')->request->all();
        $slug = '';

        if(isset($parameters['slug'])) $slug = $parameters['slug'];
        
        $redirectList = '_corporate_userslist';
        if (($slug == 'company' || $slug == 'agency' || $slug == 'retail-agency') && isset($parameters['accountId'])){
            $parameters['accountId'] = $this->accountAddEditAccountSubmit($parameters);
            $redirectList = '_corpo_accountslist';
        }else{
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $profileId = $sessionInfo['profileId'];
            if ($profileId == $this->container->getParameter('COMPANY') || $profileId == $this->container->getParameter('AGENCY') || $profileId == $this->container->getParameter('RETAIL_AGENCY')){
                $parameters['accountId'] = $sessionInfo['accountId'];
            }else{
                $parameters['accountId'] = $this->container->getParameter('TOURISTTUBE_ACCOUNT');
            }

            $redirectList = '_corporate_userslist';  
        }

        if ($parameters['fname'] || $parameters['lname']) {
            $parameters['fullName'] = $parameters['fname']." ".$parameters['lname'];
        }

        if (!isset($parameters['corpoUserProfileId']) || !$parameters['corpoUserProfileId'] || !is_numeric($parameters['corpoUserProfileId'])){
            switch($slug) {
                case 'company':
                    $parameters['corpoUserProfileId'] = $this->container->getParameter('COMPANY');
                    break;
                case 'agency':
                    $parameters['corpoUserProfileId'] = $this->container->getParameter('AGENCY');
                    break;
                case 'retail-agency':
                    $parameters['corpoUserProfileId'] = $this->container->getParameter('RETAIL_AGENCY');
                    break;
                case 'sales':
                    $parameters['corpoUserProfileId'] = $this->container->getParameter('SALES_PERSON');
                    break;
                case 'affiliate':
                    $parameters['corpoUserProfileId'] = $this->container->getParameter('AFFILIATES');
                    break;
                default:
                    $parameters['corpoUserProfileId'] = $this->container->getParameter('USERS');
                    break;                
            }
        }

        $country  = (isset($parameters['userCountry']) ? $parameters['userCountry'] : ((isset($parameters['countryId']) && $parameters['countryId']) ? $parameters['countryId'] : $parameters['countryCode']) );

        $parameters['yourCountry']        = $country;
        $parameters['defaultPublished']   = 1;
        $parameters['isChannel']          = 0;
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();

        if (isset($parameters['accessToSubAccount'])) {
            $parameters['accessToSubAccount'] = 1;
        } else {
            $parameters['accessToSubAccount'] = 0;
        }
        if (isset($parameters['accessToSubAccountUsers'])) {
            $parameters['accessToSubAccountUsers'] = 1;
        } else {
            $parameters['accessToSubAccountUsers'] = 0;
        }

        $parameters['isCorporateAccount'] = 1;
        if (!isset($parameters['cmsUserGroupId'])) $parameters['cmsUserGroupId']     = $this->container->getParameter('ROLE_CORPORATE');
        if(isset($parameters['password']) && $parameters['yourPassword'] != $parameters['confirmPassword']){
            if ($parameters['id'] != 0){
                return $this->redirectToLangRoute('_corpo_users_edit', array('id' => $parameters['id']));
            }else{
                return $this->redirectToLangRoute('_corpo_users');
            }
        }

        if ($parameters['id'] != 0) {
            $success = $this->get('UserServices')->modifyUser($parameters);
            if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
                return $this->redirectToLangRoute('_corpo_users_edit', array('id' => $parameters['id']));
            } else {
                $parameters['userId'] = $parameters['id'];

                if(isset($parameters['yourPassword']) && !empty($parameters['yourPassword'])){
                    $msg  = $this->translator->trans('Dear').$parameters['fullName']."<br>";
                    $msg .= $this->translator->trans('This email is to inform you that your information has been updated')."<br>";
                    $msg .= $this->translator->trans('Best Regards,')."<br>";
                    $msg .= $this->translator->trans('Touristtube Team');
                    $this->get('emailServices')->addEmailData($parameters['yourEmail'], $msg, $this->translator->trans('TouristTube User - change password'), 'TouristTube.com', 0);
                }

                if($success) {
                    $this->addSuccessNotification($this->translator->trans("Edited successfully."));
                } else {
                    $this->addErrorNotification($this->translator->trans("Error in editing user. Please try again"));
                }

                if( isset($parameters['corpoUserProfileId']) && $parameters['corpoUserProfileId'] == $this->container->getParameter('SALES_PERSON') )
                {
                    return $this->redirectToLangRoute($redirectList, array('slug'=>'sales') );
                }
                else
                {
                    if(isset($parameters['slug']) && $parameters['slug']){
                        return $this->redirectToLangRoute($redirectList, array('slug' => $parameters['slug']));
                    }else{
                        return $this->redirectToLangRoute('_corpo_users');    
                    }
                    
                }
            }
        } else {
            $this->container->get('CorpoUsersServices')->sendUserCreationEmail($parameters);

            if(!isset($parameters['yourPassword'])){
                $n_chars = 8;
                $parameters['yourPassword'] = $this->get('app.utils')->randomString($n_chars);
            }
            
            $parameters['parentUserId'] = $sessionInfo['userId'];
            $success = $this->get('UserServices')->generateUser($parameters);

            if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
                return $this->redirectToLangRoute('_corpo_users_add');
            } else {
                $parameters['userId'] = $success->getId();

                if(isset($parameters['yourPassword']) && !empty($parameters['yourPassword'])){
                    $body = $this->render('@Corporate/email_templates/admin/new_corpo_user.twig', $parameters)->getContent();
                    $this->get('emailServices')->addEmailData($parameters['yourEmail'], $body, $this->translator->trans('TouristTube User: User creation '), 'TouristTube.com', 0);
                }
                
                if($success) {
                    $this->addSuccessNotification($this->translator->trans("Added successfully."));
                } else {
                    $this->addErrorNotification($this->translator->trans("Added successfully."));
                }

                if( isset($parameters['corpoUserProfileId']) && $parameters['corpoUserProfileId'] == $this->container->getParameter('SALES_PERSON') )
                {
                    return $this->redirectToLangRoute($redirectList, array('slug'=>'sales') );
                }
                else
                {
                    if(isset($parameters['slug']) && $parameters['slug']){
                        return $this->redirectToLangRoute($redirectList, array('slug' => $parameters['slug']));
                    }else{
                        return $this->redirectToLangRoute('_corpo_users');    
                    }
                }
            }
        }
    }

    public function accountAddEditAccountSubmit($parameters)
    {
        $slug = $parameters['slug'];

        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $parameters['userId'] = $sessionInfo['userId'];
        $parameters['id'] =  $parameters['accountId'];
        $parameters['isActive'] = 1;
        $accountObj = Account::arrayToObject($parameters);

        $pPath = $this->get('CorpoAccountServices')->getPPath($accountObj->getParentId());

        if ($parameters['id'] != 0) {
            $result = $this->get('CorpoAccountServices')->updateAccount($accountObj);

            if(!empty($pPath) && !empty($pPath['path'])) {
                $path = $pPath['path'] . $parameters['id'] . ",";
            } else {
                $path = "," . $parameters['id'] . ",";
            }
            $success = $this->get('CorpoAccountServices')->updatePath($parameters['id'], $path);

            if ($result) return $parameters['id'];
            else return false;

        } else {
            $accountId = $this->get('CorpoAccountServices')->addAccount($accountObj);

            if(!empty($pPath) && !empty($pPath['path'])) {
                $path = $pPath['path'] . $accountId . ",";
            } else {
                $path = "," . $accountId . ",";
            }
            $success = $this->get('CorpoAccountServices')->updatePath($accountId, $path);

            if ($accountId) return $accountId;
            else return false;
        }

    }
    /**
     * controller action for getting a list of users
     *
     * @return TWIG
     */
    public function adminSearchUserAction()
    {
        $request = Request::createFromGlobals();
        $term    = strtolower($request->query->get('q', ''));
        $id      = strtolower($request->query->get('excludeId', ''));
        $limit   = 100;
        $data    = $this->get('CorpoAdminServices')->getCorpoAdminLikeUsers($term, $limit, $id);

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }
    
    /**
     *  controller action for sending email to change them to is corporate
     * 
     * @return json
     */
    public function isCorporateSendEmailAction(){
        
        $request      = Request::createFromGlobals();
        $yourEmail    = $request->request->get('yourEmail', '');
        $userId       = $request->request->get('userId', '');
        $yourUserName = $request->request->get('yourUserName', '');

        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId   = $sessionInfo['accountId'];

        $isCorporate = 1;
        $parameters = array(
            'id' => $userId,
            'yourEmail' => $yourEmail,
            'isCorporateAccount' => $isCorporate,
            'accountId' => $accountId
        );
//        print_r($parameters);exit;
        if($userId){
            $update    = $this->get('UserServices')->modifyUser($parameters);
        }else{
            $update    = $this->get('UserServices')->modifyUserByEmail($parameters);
        }

        if($update){
            $title = $this->translator->trans('User Changed To Corporate');
            $msg  = $this->translator->trans('Dear').$yourUserName."<br>";
            $msg .= $this->translator->trans('Kindly, note that your user in Touristtube is changed to corporate user')."<br>";
            $msg .= $this->translator->trans('Best Regards,')."<br>";
            $msg .= $this->translator->trans('Touristtube Team');

            $result['title'] = $title;
            $result['msg'] = $msg;
        }else{
            $errorTitle = $this->translator->trans('Error User Changing To Corporate');
            $msg  = $this->translator->trans('Dear').$yourUserName."<br>";
            $msg .= $this->translator->trans('kindly, note that an error occurred when trying to change your user to corporate')."<br>";
            $msg .= $this->translator->trans('Best Regards,')."<br>";
            $msg .= $this->translator->trans('Touristtube Team');

            $result['errorTitle'] = $errorTitle;
            $result['error'] = $error;
        }
        $this->get('emailServices')->addEmailData($yourEmail, $msg, $this->translator->trans('TouristTube User - change user to corporate'), 'TouristTube.com', 0);

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * controller action for filtering users
     *
     * @return TWIG
     */
    public function filterUserAction()
    {
        $request      = Request::createFromGlobals();
        $accountId    = $request->request->get('accountId', '');

        $accountId        = array('accountsIds' => array($accountId));
        $criteria         = array('account');

        $usersList = json_decode($this->get('UserServices')->getUsersList($criteria, $accountId),true);

        $data2['usersList']               = $usersList;

        $accountId = $userObj[0]['cu_corpoAccountId'];
        $userId = $userObj[0]['cu_id'];
        $userRole = $userObj[0]['cu_cmsUserGroupId'];
        if($userRole ==  $this->container->getParameter('ROLE_SYSTEM')){
            $data2['usersSystemRole'] = $this->container->getParameter('ROLE_SYSTEM');
        }
        
        $data['usersInfoList'] = $this->render('@Corporate/admin/users/usersTableList.twig', $data2)->getContent();

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for reseting Password
     *
     * @return JSON
     * 
     */
    public function resetPasswordAction()
    {
        $request        = Request::createFromGlobals();
        $userId         = $request->request->get('userId', '');
        $n_chars        = 8;
        $yourPassword   = $this->get('app.utils')->randomString($n_chars);

        $post    = $request->request->all();
        $error = array();
        $update = $this->get('UserServices')->UpdateUserPassword($userId, $yourPassword);

        if($update){

            $userObj = $this->get('UserServices')->getUserDetails(array('id' => $userId));
            $result['success']  = true ;
            $result['msg']      = $this->translator->trans('the password is changed an email will be sent to').' '.$userObj[0]['cu_youremail'] ;

            $msg  = $this->translator->trans('Dear').' '.$userObj[0]['cu_fullname']."<br>";
            $msg .= $this->translator->trans('This email is to inform you that your information has been updated')."<br>";
            $msg .= $this->translator->trans('You password has been changed to:').' '.$yourPassword."<br>";
            $msg .= $this->translator->trans('Best Regards,')."<br>";
            $msg .= $this->translator->trans('Touristtube Team');
            $this->get('emailServices')->addEmailData($parameters['yourEmail'], $msg, $this->translator->trans('TouristTube User - change password'), 'TouristTube.com', 0);
        }else{
            $result['success']  = false ;
            $result['msg']      = $this->translator->trans("Sorry we Couldn't reset your password") ;
        }

        $res = new Response(json_encode($result));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    /**
     * controller action for disallowing user approval
     *
     * @return TWIG
     */
    public function userDisallowAction($id,$slug = '')
    {
        $success = $this->get('CorpoUsersServices')->allow($id, 0);

        if($success) {
            $this->addSuccessNotification($this->translator->trans("Disallowing user approval successful."));
        } else {
            $this->addErrorNotification($this->translator->trans("Error in disallowing user approval."));
        }

        if($slug){
            return $this->redirectToLangRoute('_corporate_userslist', array('slug' => $slug));
        }else{
            return $this->redirectToLangRoute('_corpo_users');    
        }
    }

    /**
     * controller action for disallowing user approval
     *
     * @return TWIG
     */
    public function userAllowAction($id,$slug = '')
    {
        $success = $this->get('CorpoUsersServices')->allow($id, 1);

        if($success) {
            $this->addSuccessNotification($this->translator->trans("Allowing user approval successful."));
        } else {
            $this->addErrorNotification($this->translator->trans("Error in allowing user approval."));
        }

        if($slug){
            return $this->redirectToLangRoute('_corporate_userslist', array('slug' => $slug));
        }else{
            return $this->redirectToLangRoute('_corpo_users');    
        }
    }

    /**
     * controller action for getting a list of users
     *
     * @return TWIG
     */
    public function userComboAction(Request $request)
    {
        $res = $this->get('CorpoUsersServices')->getUserCombo($request);
        
        return $res;
    }
}
