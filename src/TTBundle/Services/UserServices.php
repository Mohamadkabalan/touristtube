<?php

namespace TTBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use TTBundle\Entity\CmsUsers;
use TTBundle\Entity\CmsUserGroup;
use TTBundle\Entity\CmsMobileToken;
use TTBundle\Entity\CmsTubers;
use TTBundle\Entity\CmsBag;
use TTBundle\Entity\CmsBagitem;
use RestBundle\Entity\OauthAccessToken;
use RestBundle\Entity\OauthRefreshToken;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use TTBundle\Services\Sha256Salted;
use TTBundle\Services\EmailServices;

class UserServices
{
    protected $em;
    protected $utils;
    protected $sha256Salted;
    protected $emailServices;
    protected $container;

    public function __construct(EntityManager $em, Utils $utils, Sha256Salted $sha256Salted, $templating, EmailServices $emailservices, ContainerInterface $container)
    {
        $this->em = $em;
        $this->utils = $utils;
        $this->sha256Salted = $sha256Salted;
        $this->templating = $templating;
        $this->emailServices = $emailservices;
        $this->container = $container;
        $this->translator = $this->container->get('translator');
    }

    /*
     *
     * This method called to generate a user from the service with specifc params.
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user generated or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function generateUser($user = array())
    {
        if (empty($user['password']) && empty($user['yourPassword'])) {
            return false;
        }
        // if password is not set then this is been called from ajax form with yourpassword as value of password
        if (empty($user['password'])) {
            $user['password'] = $user['yourPassword'];
        }

        $user['salt'] = $this->utils->randomString(64);
        $encodedPassword = $this->sha256Salted->encodePassword($user['password'], $user['salt']);
        $user['password'] = $encodedPassword;

        $encodedYourPassword = $this->sha256Salted->encodePassword($user['yourPassword'], NULL);
        $user['yourPassword'] = $encodedYourPassword;

        if (isset($user['yourBday']) && $user['yourBday']) {
            $user['yourBday'] = date("Y-m-d", strtotime($user['yourBday']));
        }

        /**set other user's allow_approve_all_user value to 0 */
        if(isset($user['allowApprove']) && $user['allowApprove']) {
            $this->em->getRepository('CorporateBundle:CmsUsers')->unsetAllowApprove($user['accountId']);
        }
        return $this->em->getRepository('TTBundle:CmsUsers')->generateUser($user);
    }

    /*
     *
     * This method checks for duplicate entry userName
     * Can be used both in modify and adding user
     *
     * @param $userId
     * @param $userName
     *
     * @return boolean true or false
     * @author Anna lou Parejo <anna.parejo@touristtube.com>
     */

    public function checkDuplicateUserName($userId, $userName)
    {
        return $this->em->getRepository('TTBundle:CmsUsers')->checkDuplicateUserName($userId, $userName);
    }

    /*
     *
     * This method checks for duplicate entry email
     * Can be used both in modify and adding user
     *
     * @param $userId
     * @param $email
     *
     * @return boolean true or false
     * @author Anna lou Parejo <anna.parejo@touristtube.com>
     */

    public function checkDuplicateUserEmail($userId, $email)
    {
        return $this->em->getRepository('TTBundle:CmsUsers')->checkDuplicateUserEmail($userId, $email);
    }

    /*
     *
     * This suggests for unique usernames
     *
     * @param $username
     *
     * @return array
     * @author Anna lou Parejo <anna.parejo@touristtube.com>
     */

    public function suggestUserNameNew($username = '')
    {
        if ($username == '' || empty($username)) {
            return false;
        }

        $username = strtolower($username);
        $userNamesArr = array();
        $i = 1;
        $k = 0;

        while ($i < 1000000) {
            $newUserName = $username . '' . $i;
            if (!$this->checkDuplicateUserName(0, $newUserName)) {
                array_push($userNamesArr, $newUserName);
                $k++;
            }
            if ($k >= 3) break;
            $i++;
        }

        return $userNamesArr;
    }

    /*
     * This method should return the list of users using the service from the database.
     * This should get a general list of user or even by specific criteria to be sent as param using filter param like : filter=role value=corp...
     *
     * @param $criteria array('account','role')
     * @param $values   Associative array('accountsIds' => array(1,7,5) , 'rolesNames' => array('UserRole', 'AdminRole' , 'CorporateRole', ... ) )
     */

    public function getUsersList($criteria = array(), $values = array())
    {
        $users = $this->em->getRepository('TTBundle:CmsUsers')->getUsersList($criteria, $values);
        return json_encode($users);;
    }

    /*
     * This method is used to delete a user and all his media
     * @param integer $user_id the cms_users id
     *
     * @return boolean success or fail
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function deleteUser($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->em->getRepository('TTBundle:CmsUsers')->deleteUser($id);
    }

    /*
     * This method is used to return user info from loging credentials
     * @param string username
     * @param string password
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userInfo($username = '', $password = '')
    {
        if (empty($username) || empty($password)) {
            $result['error'] = $this->translator->trans('Empty username or password');
            return json_encode($result);
        }
        $password = $this->sha256Salted->encodePassword($password, NULL);
        $user = $this->em->getRepository('TTBundle:CmsUsers')->getUserInfo($username, $password);
        if (!$user) {
            $result['error'] = $this->translator->trans('Invalid Credentials');
            return json_encode($result);
        } else {
            return json_encode($user);
        }
    }

    public function getUserInfo($username = '', $password = '')
    {
        if (empty($username) || empty($password)) {
            return false;
        }
        $password = $this->sha256Salted->encodePassword($password, NULL);
        return $this->em->getRepository('TTBundle:CmsUsers')->getUserInfo($username, $password);
    }

    /*
     * This method is used to update user password
     * @param integer userId
     * @param password new_password
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function validateUserLengthPassword($password)
    {
        $ret = array();
        if (strlen(trim($password)) < 8 || empty($password)) {
            $ret['success'] = false;
            $ret['message'] = $this->translator->trans('Your password is too short');
            $ret['error_no'] = 2;
        }
        return $ret;
    }

    /*
     * This method is used to update user password to new format
     * User has no salt ( new password ) we need to update his records with the password he already had ( setting salt and password columns ) for this user.
     * @param $userId
     * @param $password
     *
     * @return $response
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function updatePasswordNewFormat($userId, $password)
    {
        if (!$userId || !$password) {
            return;
        }

        $success = true;
        $message = '';

        $params = array();
        $params['userId'] = $userId;
        $params['salt'] = $this->utils->randomString(64);
        $encodedPassword = $this->sha256Salted->encodePassword($password, $params['salt']);
        $params['password'] = $encodedPassword;

        $encodedYourPassword = $this->sha256Salted->encodePassword($password, NULL);
        $params['yourpassword'] = $encodedYourPassword;

        $update = $this->em->getRepository('TTBundle:CmsUsers')->updateUserPassword($params);

        if ($update) {
            $success = true;
            $success = 'success';
        } else {
            $success = false;
            $message = $this->translator->trans("Couldn't process. Please try again later");
        }

        $ret = array();
        $ret['success'] = $success;
        $ret['message'] = $message;
        return $ret;
    }

    /*
     * This method is used to update user password
     * @param integer userId
     * @param password new_password
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function updateUserPassword($user_id, $new_pass)
    {
        $validatePass = $this->validateUserLengthPassword($new_pass);

        if (isset($validatePass['success']) && $validatePass['success'] == false) {
            return $validatePass;
        } else {
            if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $new_pass)) {
                $validatePass['success'] = false;
                $validatePass['message'] = $this->translator->trans('Your password is not secure');
                $validatePass['error_no'] = 2;
                return $validatePass;
            }
        }

        $ret = array();

        $user['userId'] = $user_id;
        $user['salt'] = $this->utils->randomString(64);
        $encodedPassword = $this->sha256Salted->encodePassword($new_pass, $user['salt']);
        $user['password'] = $encodedPassword;

        $encodedYourPassword = $this->sha256Salted->encodePassword($new_pass, NULL);
        $user['yourpassword'] = $encodedYourPassword;

        $update = $this->em->getRepository('TTBundle:CmsUsers')->updateUserPassword($user);

        if ($update) {
            $ret['success'] = true;
        } else {
            $ret['success'] = false;
            $ret['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }
        return $ret;
    }

    /*
     *
     * This method called to edit a users information.
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function modifyUser($user = array())
    {
        if ((isset($user['id']) && !empty($user['id']))) {
            /**set other user's allow_approve_all_user value to 0 */
            if(isset($user['allowApprove']) && $user['allowApprove']) {
                $this->em->getRepository('CorporateBundle:CmsUsers')->unsetAllowApprove($user['accountId']);
            }
            return $this->em->getRepository('TTBundle:CmsUsers')->modifyUser($user);
        }

        return false;
    }

    /**
     * sets the users profile pic
     * @param type $user_id the user
     * @param type $filename the src image
     * @param type $path the path profile pic (230x230)
     * @return boolean true|false if success|fail
     */
    public function userSetProfilePic($user_id, $filename, $path)
    {
        $userInfo = $this->getUserInfoById($user_id);
        $oldProfile = $userInfo['cu_profilePic'];

        $oldProfile = str_replace('Profile_', '', $oldProfile);
        $mask = $this->container->get("TTFileUtils")->globFiles($path, "*" . $oldProfile);
        foreach ($mask as $thumb) {
            $this->container->get("TTFileUtils")->unlinkFile($thumb);
        }

        $save['updateByThisUserId'] = $user_id;
        $save['p_profile_picture'] = $filename;
        $this->container->get('CorpoEmployeesServices')->updateEmployee($save);

        $save['id'] = $user_id;
        $save['profilePic'] = $filename;
        $save['profileId'] = 0;

        return $this->modifyUser($save);
    }

    /**
     * add the emails notifications
     * @param string $email the email
     * @param integer $notify 1 or 0 if false
     * @return true | false
     */
    public function addNotificationsEmails($email, $notify)
    {
        return $this->em->getRepository('TTBundle:CmsNotificationsEmails')->addNotificationsEmails($email, $notify);
    }

    /**
     * get the emails notifications
     * @param string $email the email
     * @return emails notifications record
     */
    public function getNotificationsEmails($email)
    {
        return $this->em->getRepository('TTBundle:CmsNotificationsEmails')->getNotificationsEmails($email);
    }

    /**
     * checks if a user's password is correct
     * @param integer $user_id
     * @param string $old_pass
     * @param string $username
     * @return boolean true|false
     */
    public function userPasswordCorrect($user_id, $old_pass, $username = '')
    {
        $old_pass = $this->sha256Salted->encodePassword($old_pass, NULL);
        return $this->em->getRepository('TTBundle:CmsUsers')->userPasswordCorrect($user_id, $old_pass, $username);
    }

    /*
    * @userAlreadyAuthorized function will check weather the user is already authorized with twitter or any other social network
    */
    public function userAlreadyAuthorized($user_id = null, $account_type = '', $status = '1')
    {
        return $this->em->getRepository('TTBundle:CmsUsersSocialTokens')->userAlreadyAuthorized($user_id, $account_type, $status);
    }

    /*
    * @updateUserSocialCredential function will update the user credential from status 0 to 1 for respective social media
    */
    public function updateUserSocialCredential($user_id = null, $account_type = 'twitter', $status = '1')
    {
        return $this->em->getRepository('TTBundle:CmsUsersSocialTokens')->updateUserSocialCredential($user_id, $account_type, $status);
    }

    /*
    * @setUserSocialCredential function will store the user credential for respective social network
    */
    public function setUserSocialCredential($user_id = null, $access_token, $account_type = 'twitter')
    {
        return $this->em->getRepository('TTBundle:CmsUsersSocialTokens')->setUserSocialCredential($user_id, $access_token, $account_type);
    }

    /**
     * delete or deactivates all user's entities
     * @param integer $user_id
     * @param string $action (delete or deactivate)
     * @return boolean true|false
     */
    public function userDeleteDeactivateEntities($user_id, $action)
    {
        return $this->em->getRepository('TTBundle:CmsUsers')->userDeleteDeactivateEntities($user_id, $action);
    }

    /*
     *
     * This method called to edit a users information.
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Anthony Malak <anthony@touristtube.com>
     */

    public function modifyUserByEmail($user = array())
    {
        if ((isset($user['yourEmail']) && !empty($user['yourEmail']))) {
            return $this->em->getRepository('TTBundle:CmsUsers')->modifyUserByEmail($user);
        }

        return false;
    }

    /*
     * Sets the user session values
     * @param array userId
     * @return array()
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userSetSession($userId)
    {
        $userInfo = array();
        if ($userId) {
            $row = $this->em->getRepository('TTBundle:CmsUsers')->settingSession($userId);
            if (empty($row)) {
                return false;
            }

            $userInfo['id'] = $row[0]['id'];
            $userInfo['YourUserName']      = $row[0]['yourusername'];
            $userInfo['u_yourusername']    = $row[0]['yourusername'];
            $userInfo['FullName']          = $row[0]['fullname'];
            $userInfo['u_fullname']        = $row[0]['fullname'];
            $userInfo['fname']             = $row[0]['fname'];
            $userInfo['lname']             = $row[0]['lname'];
            $userInfo['gender']            = $row[0]['gender'];
            $userInfo['country']           = $row[0]['yourcountry'];
            $userInfo['display_fullname']  = $row[0]['displayFullname'];
            $userInfo['u_displayFullname'] = $row[0]['displayFullname'];
            $userInfo['profile_Pic']       = $row[0]['profilePic'];
            $userInfo['email']             = $row[0]['youremail'];
            $userInfo['isChannel']         = $row[0]['ischannel'];
            $userInfo['isCorporateAccount']= $row[0]['isCorporateAccount'];
        }
        return $userInfo;
    }

    /**
     * Used to store user IP address and browser/device info
     * @param array of ($ip_address,$forwarded_ip_address,$user_agent, $user_id )
     * @return doctine object
     */
    function userLoginTrack($params = array())
    {
        if (empty($params)) {
            return;
        }

        $row = $this->em->getRepository('TTBundle:CmsUsers')->insertUserLoginTrack($params);
        return $row;
    }

    /*
     *
     * This method is used to enter session to CmsTubers
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userToSession($user = array())
    {
        if (isset($user['uid']) && !empty($user['uid'])) {
            return $this->em->getRepository('TTBundle:CmsTubers')->userToSession($user);
        } else {
            return false;
        }
    }

    /*
     *
     * This method is used to enter session to CmsTubers
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userEndSession($uuid)
    {
        if ($uuid) {
            return $this->em->getRepository('TTBundle:CmsTubers')->userEndSession($uuid);
        } else {
            return false;
        }
    }

    /*
     *
     * This method is used to enter session to CmsMobileToken
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function userToMobileToken($params = array())
    {
        if (isset($params['uid']) && !empty($params['uid'])) {
            return $this->em->getRepository('TTBundle:CmsMobileToken')->userToMobileToken($params);
        } else {
            return false;
        }
    }

    /*
     *
     * This method is used to clear all token when user is logging out
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteMobileToken($params)
    {
        if (empty($params)) {
            return false;
        }

        return $this->em->getRepository('TTBundle:CmsMobileToken')->deleteMobileToken($params);
    }

    /*
     * Updating oauth access token
     *
     * @params
     *
     * @return boolean success or fail if user is edited or not
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function updateOauthAccessToken($params = array())
    {
        if (isset($params['accessToken']) && !empty($params['accessToken'])) {
          $this->em->getRepository('TTBundle:CmsMobileToken')->updateOauthAccessToken($params);
        } else {
               return false;
        }
    }

    /*
     * Deleting access token when logging out
     *
     * @$token
     *
     * @return boolean success or fail if user is edited or not
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteAccessToken($token)
    {
        if ($token) {
            $this->em->getRepository('RestBundle:OauthAccessToken')->deleteAccessToken($token);
        } else {
            return false;
        }
    }

    /*
     * Deleting refresh token when logging out
     *
     * @$token
     *
     * @return boolean success or fail if user is edited or not
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteRefreshToken($token)
    {
        if ($token) {
            $this->em->getRepository('RestBundle:OauthRefreshToken')->deleteRefreshToken($token);
        } else {
            return false;
        }
    }

    /*
     *
     * This method is used to update users longitude & latitude in CmsUsers
     *
     * @param array of user fields
     *
     * @return boolean success or fail if user is edited or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userSetProfilePosition($user = array())
    {
        if (isset($user['userId']) && !empty($user['userId'])) {
            return $this->em->getRepository('TTBundle:CmsTubers')->userSetProfilePosition($user);
        } else {
            return false;
        }
    }

    /*
     * This method gets CmsChannel data of a user limit 1
     * @param userId @return boolean success or fail if user is edited or not
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userDefaultChannelGet($userId)
    {
        if (isset($userId) && !empty($userId)) {
            return $this->em->getRepository('TTBundle:CmsChannel')->userDefaultChannelGet($userId);
        } else {
            return false;
        }
    }

    /*
     *
     * This method gets user data per array being passed
     *
     * @param array()
     *
     * @return user array or false if fail
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserDetails($params = array())
    {
        if (isset($params) && !empty($params)) {
            return $this->em->getRepository('TTBundle:CmsUsers')->getUserDetails($params);
        } else {
            return false;
        }
    }

    /*
    * @userUpdateFbUser
    */
    public function userUpdateFbUser($user_id = null, $fb_user, $pass)
    {
        $salt = $this->utils->randomString(64);
        $encodedPassword = $this->sha256Salted->encodePassword($pass, $salt);
        $password = $encodedPassword;

        $encodedYourPassword = $this->sha256Salted->encodePassword($pass, NULL);
        $yourPassword = $encodedYourPassword;

        return $this->em->getRepository('TTBundle:CmsUsers')->userUpdateFbUser($user_id, $fb_user, $salt, $password, $yourPassword);
    }

    /*
     *
     * This method gets user data per id
     *
     * @param userId
     * @param $isArray: If we want array results
     *
     * @return user array or false if fail
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserInfoById($userId, $isArray = true)
    {
        if ($userId) {
            if ($isArray) {
                return $this->em->getRepository('TTBundle:CmsUsers')->getUserInfoById($userId);
            } else {
                $result = $this->em->getRepository('TTBundle:CmsUsers')->findBy(array('id' => $userId, 'published' => 1));
                if (sizeof($result)) {
                    $result['profile_empty_pic'] = 0;
                    if ($result[0]->getProfilePic() == '') {
                        $result[0]->setProfilePic('he.jpg');
                        $result['profile_empty_pic'] = 1;
                        if ($result[0]->getGender() == 'F') {
                            $result[0]->setProfilePic('she.jpg');
                        }
                    }
                    if ($result[0]->getProfileId() == 0) $result['profile_empty_pic'] = 1;
                }
                return $result;
            }
        } else {
            return false;
        }
    }

    /*
     *
     * This method gets user data per array being passed
     *
     * @param array()
     *
     * @return user array or false if fail
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function checkUserEmailMD5($userCredential = '')
    {
        if (isset($userCredential) && !empty($userCredential)) {
            return $this->em->getRepository('TTBundle:CmsUsers')->checkUserEmailMD5($userCredential);
        } else {
            return false;
        }
    }

    /*
     * This method delete user
     */
    public function deleteUserEmailMD5($userCredential = '')
    {
        if (isset($userCredential) && !empty($userCredential)) {
            return $this->em->getRepository('TTBundle:CmsUsers')->deleteUserEmailMD5($userCredential);
        } else {
            return false;
        }
    }

    /*
     *
     * This method activating users account
     * Setting published = 1
     *
     * @param $userId
     * @param $force -- reactivating for old users
     *
     * @return userObj or false if fail
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */

    public function activateUsersAccount($userId = 0, $inactive = false)
    {
        $userInfo = $this->getUserDetails(array('id' => $userId));

        if (empty($userInfo)) return false;

        $registeredDate = $userInfo[0]['cu_registereddate']->format('Y-m-d');
        $dateNow = date('Y-m-d', strtotime($registeredDate));
        $dateNowBefore = date('Y-m-d', time() - 604800);

        if ($dateNow >= $dateNowBefore || $inactive) {
            return $this->em->getRepository('TTBundle:CmsUsers')->modifyUser(array('id' => $userId, 'published' => 1));
        } else {
            return false;
        }
        return false;
    }

    /*
     *
     * This method gets user data per array being passed
     *
     * @param array()
     *
     * @return user array or false if fail
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserByEmailYourUserName($userCredential = '')
    {
        if (isset($userCredential) && !empty($userCredential)) {
            return $this->em->getRepository('TTBundle:CmsUsers')->getUserByEmailYourUserName($userCredential);
        } else {
            return false;
        }
    }

    /*
     *
     * This method gets user data per array being passed
     *
     * @param array()
     *
     * @return user array or false if fail
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userResetPassword($param = array())
    {
        $user = $this->getUserByEmailYourUserName($param['email']);

        if (!$user || empty($user) || empty($param)) return false;

        $resetPassword = md5($user['cc_id'] . '' . $user['cc_youremail']);

        $userFullName = $user['cc_fullname'];
        $userEmail = $user['cc_youremail'];
        $userName = $user['cc_yourusername'];

        $corpo = '';
        if (isset($param['iscorpo']) && !empty($param['iscorpo'])) {
            $corpo = 'corporate';
        }

        $change_pass_lnk = $this->utils->generateLangURL($param['langCode'], $param['changePassLink'] . $resetPassword, $corpo);
        $tellus_lnk = $this->utils->generateLangURL($param['langCode'], $param['tellUsLink'] . $resetPassword, $corpo);
        $tt_help_lnk = $this->utils->generateLangURL($param['langCode'], '/help', $corpo);
        /*
         * Send email here
         */
        $emailVars['to_email'] = $userEmail;
        $emailVars['owner_name'] = $userFullName;
        $emailVars['user_name'] = $userName;
        $emailVars['change_pass_lnk'] = $change_pass_lnk;
        $emailVars['tellus_lnk'] = $tellus_lnk;
        $emailVars['tt_help_lnk'] = $tt_help_lnk;

        $subject = $this->translator->trans("TouristTube Forgotten Password");
        $notifcationTwig = $param['notificationTwig'];

        $msg = $this->templating->render($notifcationTwig, $emailVars);
        $status = $this->emailServices->addEmailData($userEmail, $msg, $subject, 'TouristTube.com', 0);
        if (!$status) {
            $ret = array('status' => 'error', 'msg' => $this->translator->trans('An Error occured while sending your password reset information.<br/><br/>Kindly contact our support department on support@touristube.com'));
        } else {
            $ret = array('status' => 'ok', 'msg' => $this->translator->trans("<b> We've emailed you password reset instructions. Check your email.</b><br/><br/>You can keep this page opened while you check your email. If you don't receive the email within a minute or two check your email's spam and junk filters."));
        }
        return $ret;
    }

    /*
     *
     * This method gets sent activation email to users
     *
     * @param $params - array of criteria
     *
     * @return user array or false if fail
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */

    public function sendRegisterActivationEmail($params = array())
    {
        $user = $this->getUserDetails(array('id' => $params['userId']));

        if (empty($user)) {
            return false;
        }

        $userFullName = $user[0]['cu_fullname'];
        $userEmail = $user[0]['cu_youremail'];
        $activation = md5($params['userId'] . '' . $userEmail);
        $corpo = '';
        if (isset($user[0]['cu_isCorporateAccount']) && !empty($user[0]['cu_isCorporateAccount'])) {
            $corpo = 'corporate';
        }

        $activationLnk = $this->utils->generateLangURL($params['langCode'], $params['activationLink'] . $activation, $corpo);
        /*
         * Send email here
         */
        $emailVars = array();
        $emailVars['to_email'] = $userEmail;
        $emailVars['displayTopHead'] = $userFullName;
        $emailVars['activate_lnk'] = $activationLnk;
        $emailVars['activate_lnkname'] = _('activate account');

        $subject = _("Activate your TouristTube account");
        $notifcationTwig = 'emails/emailActivateUser.html.twig';

        $msg = $this->templating->render($notifcationTwig, $emailVars);
        $status = $this->emailServices->addEmailData($userEmail, $msg, $subject, 'TouristTube.com', 0);

        return $status;
    }

    /*
     *
     * This method gets user account status is_active is active or not
     *
     * @param array()
     *
     * @return boolean true or false
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserAccountStatus($params = array())
    {
        if ($params) {
            return $this->em->getRepository('TTBundle:CmsUsers')->getUserAccountStatus($params);
        } else {
            return false;
        }
    }

    /*
     *
     * This method gets user roles
     *
     * @param array()
     *
     * @return boolean true or false
     * @author Anthony Malak <fa@touristtube.com>
     */

    public function getUserRoles($params)
    {
        $roles = $this->em->getRepository('TTBundle:CmsUserGroup')->getUserRoles($params);

        if (isset($params['userRole']) && !empty($params['userRole'])) {
            $tmpRoles = array();
            //
            for ($i = 0; $i < count($roles); $i++) {
                if ($params['userRole'] == $params['ROLE_SYSTEM']) {
                    if ($roles[$i]['cu_id'] == $params['ROLE_SYSTEM']) {
                        $tmpRoles[$i] = $roles[$i];
                    } elseif ($roles[$i]['cu_id'] == $params['ROLE_ADMIN']) {
                        $tmpRoles[$i] = $roles[$i];
                    } elseif ($roles[$i]['cu_id'] == $params['ROLE_CORPORATE']) {
                        $tmpRoles[$i] = $roles[$i];
                    } elseif ($roles[$i]['cu_id'] == $params['ROLE_API']) {
                        $tmpRoles[$i] = $roles[$i];
                    }
                } elseif ($params['userRole'] == $params['ROLE_ADMIN']) {
                    if ($roles[$i]['cu_id'] == $params['ROLE_ADMIN']) {
                        $tmpRoles[$i] = $roles[$i];
                    } elseif ($roles[$i]['cu_id'] == $params['ROLE_CORPORATE']) {
                        $tmpRoles[$i] = $roles[$i];
                    }
                } elseif ($params['userRole'] == $params['ROLE_CORPORATE']) {
                    if ($roles[$i]['cu_id'] == $params['ROLE_CORPORATE']) {
                        $tmpRoles[$i] = $roles[$i];
                    }
                }
            }
            $roles = $tmpRoles;
        }

        return $roles;
    }

    /*
     *
     * This method gets user info if the passed username/email and password are valid for the user
     *
     * @param username
     * @param password
     *
     * @return user object if valid or false if credentials are invalid
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserByLogin($username = '', $password = '')
    {
        if (empty($username) || empty($password)) {
            return false;
        }

        $userInfo = $this->em->getRepository('TTBundle:CmsUsers')->getUserByEmailYourUserName($username);

        if ($userInfo) {

            $userPassword = (isset($userInfo['cc_password']) && !empty($userInfo['cc_password'])) ? $userInfo['cc_password'] : $userInfo['cc_yourpassword'];

            if ($this->sha256Salted->isPasswordValid($userPassword, $password, $userInfo['cc_salt'])) {
                if (empty($userInfo['cc_salt'])) {
                    $this->updatePasswordNewFormat($userInfo['cc_id'], $password);
                }
                return $userInfo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     *
     * This method handles the functionality od user login to our system
     *
     * @param array()
     *
     * @return boolean true or false
     * @author Anthony Malak <fa@touristtube.com>
     */

    public function userLogin($params)
    {

        $secret_key = "fakesecretkey";
        $userInfo = $this->getUserByLogin($params['username'], $params['password']);

        if ($userInfo) {

            $data = time() . "_" . $userInfo['cc_id'];
            $token = hash('sha512', $secret_key . $data);
            $params['uid'] = $token;

            $params['userId'] = $userInfo['cc_id'];
            $params['keepMeLogged'] = 1;
            $params['socialToken'] = '';

            return $this->userToSession($params) ? array('token' => $token, 'row' => $userInfo) : false;
        } else {
            return false;
        }
    }

    /*
     * This method handles retrieves user Interested in list
     *
     *
     * @return array()
     */

    public function getInterestedInList($lang = 'en')
    {
        $return = array();
        $interestedInList = $this->em->getRepository('TTBundle:CmsIntrestedin')->getInterestedInList($lang);
        $results = array();
        if ($interestedInList) {
            foreach ($interestedInList as $item) {
                $item_list = array();
                $item_list['id'] = $item['i_id'];
                $item_list['title'] = $this->utils->htmlEntityDecode($item['i_title']);
                if ($item['mli_title'] != '') $item_list['title'] = $this->utils->htmlEntityDecode($item['mli_title']);
                $results[] = $item_list;
            }
            $return['success'] = true;
            $return['status'] = 'ok';
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }
        $return['data'] = $results;

        return $return;
    }

    /*
     * This method handles retrieves user's bags list
     *
     * @param userId
     *
     * @return array()
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getUserBagList($userId, $lang = 'en')
    {
        $return = array();
        $bagLists = $this->em->getRepository('TTBundle:CmsBag')->getUserBagList($userId);
        $media_bucket_url = $this->container->get("TTRouteUtils")->getMediaBucketURL();

        $results = array();
        if ($bagLists) {
            foreach ($bagLists as $bag) {
                $item = array();
                $item['id'] = $bag['cb_id'];
                $item['name'] = $this->utils->htmlEntityDecode($bag['cb_name']);
                $item['namealt'] = $this->utils->cleanTitleDataAlt($bag['cb_name']);
                $item['link'] = $this->utils->generateLangURL($lang, '/bag-' . $bag['cb_id']);
                $item['img'] = '';
                if ($bag['cb_imgname']) {
                    $item['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($bag['cb_imgname'], $bag['cb_imgpath'], 0, 0, 284, 162, 'bagthumb-284162');
                }

                $item['image'] = $item['img'];
                if ($media_bucket_url != '' && $item['img'] != '') {
                    $explode_array_media = explode($media_bucket_url, $item['img']);
                    $item['image'] = $explode_array_media[1];
                }
                if (substr($item['image'], 0, 1) == '/') $item['image'] = ltrim($item['image'], '/');

                $item['count'] = $this->getUserBagItemsCount($userId, $bag['cb_id']);
                $results[$item['id']] = $item;
            }
            $return['success'] = true;
            $return['count'] = sizeof($bagLists);
        } else {
            $return['success'] = false;
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
            $return['count'] = 0;
        }
        $return['status'] = 'ok';
        $return['data'] = $results;

        return $return;
    }

    /*
     * This method handles get bag item count
     *
     * @param userId
     * @param $bagId
     *
     * @return integer count
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getUserBagItemsCount($userId, $bagId)
    {
        return $this->em->getRepository('TTBundle:CmsBagitem')->getUserBagItemsCount($userId, $bagId);
    }

    /*
     * This method handles get bag item list
     *
     * @param $user_id
     * @param $bag_id
     * @param $entity_type
     *
     */
    public function userBagItemsList($user_id, $bag_id = 0, $entity_type = 0)
    {
        return $this->em->getRepository('TTBundle:CmsBagitem')->userBagItemsList($user_id, $bag_id, $entity_type, $this->container);
    }

    /*
     * This method handles get bag item list
     *
     * @param $user_id
     * @param $bag_id
     *
     */
    public function returnBagItemData($user_id, $bag_id = 0, $lang)
    {
        $link = '/media/discover/';
        $media_bucket_url = $this->container->get("TTRouteUtils")->getMediaBucketURL();
        $BagItm = $this->userBagItemsList($user_id, $bag_id);
        $poi_Array = array();
        foreach ($BagItm as $item) {
            $bag_object = array();
            $media_hotels = array();
            $media_resto = array();
            $locationText = '';
            $dimagepath = '';
            $stars = 0;
            $linkReservation = '';
            $checkIn = '';
            $checkOut = '';
            $item_id = $item['b_itemId'];
            $eachtype = $item['b_type'];
            $eachtypetext = '';

            if ($eachtype == $this->container->getParameter('SOCIAL_ENTITY_HOTEL')) {
                $eachtypetext = 'hotel';
                $bag_object['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');

                $hotels = $item;
                if ($hotels['h_id'] == '') continue;

                $log = $hotels['h_longitude'];
                $lat = $hotels['h_latitude'];
                $page_link = $this->container->get('TTRouteUtils')->returnHotelDetailedLink($lang, $hotels['h_name'], $hotels['h_id']);
                $bg_name = $this->utils->htmlEntityDecode($hotels['h_name']);
                $bg_namealt = $this->utils->cleanTitleDataAlt($hotels['h_name']);
                $stars = intval($hotels['h_stars']);
                $locationText = $hotels['h_address'];
                if ($locationText == '') $locationText = $hotels['h_street'];
                if ($hotels['hw_city'] != '' && $locationText == '') {
                    $city_name = $this->utils->htmlEntityDecode($hotels['hw_city']);
                    if ($city_name) {
                        if ($locationText) $locationText .= '<br/>';
                        $locationText = $city_name;
                    }
                    $state_name = '';
                    if ($hotels['hws_state'] != '') {
                        $state_name = $this->utils->htmlEntityDecode($hotels['hws_state']);
                        if ($city_name == '') $locationText .= '<br/>';
                        $locationText .= ', ' . $state_name;
                    }
                    $country_name = $this->utils->htmlEntityDecode($hotels['hc_country']);
                    if ($country_name != '') {
                        if ($city_name == '' && $state_name == '') $locationText .= '<br/>';
                        $locationText .= ', ' . $country_name;
                    }
                }
                $cityName = $locationText;
                $dimagepath = 'media/images/';
                $dimage = 'hotel-icon-image2.jpg';
                if ($hotels['hi_filename'] != '') {
                    $dimagepath = 'media/hotels/' . $hotels['h_id'] . '/' . $hotels['hi_location'] . '/';
                    $dimage = $hotels['hi_filename'];
                }
                $bag = $item_id;
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/placestostay_icon.png');
                if ($hotels['hhr_reference'] != '') {
                    $eachtypetext = 'hotels-reserved';
                    if (strlen($bg_name) > 70) {
                        $bg_name = $this->utils->getMultiByteSubstr($bg_name, 73, NULL, $lang);
                    }
                    $bag_object['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'discover284162');

                    $bag_object['image'] = $bag_object['img'];
                    if ($media_bucket_url != '' && $bag_object['img'] != '') {
                        $explode_array_media = explode($media_bucket_url, $bag_object['img']);
                        $bag_object['image'] = $explode_array_media[1];
                    }
                    if (substr($bag_object['image'], 0, 1) == '/') $bag_object['image'] = ltrim($bag_object['image'], '/');

                    $bag_object['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');

                    if ($stars > 5) $stars = 5;
                    $bag_object['stars'] = $stars;
                    $bag_object['type'] = $eachtypetext;
                    $bag_object['entity_type'] = $eachtype;
                    $bag_object['name'] = $bg_name;
                    $bag_object['namealt'] = $bg_namealt;
                    $bag_object['entityId'] = $bag_object['bag'] = $bag;
                    $bag_object['id'] = $item['b_id'];
                    $bag_object['icon'] = $icon;
                    $bag_object['link'] = $page_link;
                    $bag_object['address'] = $cityName;
                    $bag_object['latitude'] = $lat;
                    $bag_object['logitude'] = $log;

                    $bag_object['checkIndate'] = date("d", strtotime($hotels['hhr_fromDate']));
                    $bag_object['checkOutdate'] = date("d", strtotime($hotels['hhr_toDate']));
                    $bag_object['checkIn'] = date("M Y l", strtotime($hotels['hhr_fromDate']));
                    $bag_object['checkOut'] = date("M Y l", strtotime($hotels['hhr_toDate']));
                    $bag_object['reference'] = $hotels['hhr_reference'];
                    $bag_object['linkReservation'] = $this->container->get('TTRouteUtils')->returnBookingDetailsLink($lang, $hotels['hhr_reference']);
                    $poi_Array[] = $bag_object;

                    continue;
                }
            } else if ($eachtype == $this->container->getParameter('SOCIAL_ENTITY_HOTEL_RESERVATION')) {
                $eachtypetext = 'hotel';
                $bag_object['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');

                $hotels = $item;
                if ($hotels['hrh_id'] == '') continue;


                $log = $hotels['hrh_longitude'];
                $lat = $hotels['hrh_latitude'];
                $page_link = $this->container->get('TTRouteUtils')->returnHotelDetailedLink($lang, $hotels['hrh_name'], $hotels['hrh_id']);
                $bg_name = $this->utils->htmlEntityDecode($hotels['hrh_name']);
                $bg_namealt = $this->utils->cleanTitleDataAlt($hotels['hrh_name']);
                $stars = intval($hotels['hrh_stars']);
                $locationText = $hotels['hrh_address'];
                if ($locationText == '') $locationText = $hotels['hrh_street'];
                if ($hotels['hrhw_city'] != '' && $locationText == '') {
                    $city_name = $this->utils->htmlEntityDecode($hotels['hrhw_city']);
                    if ($city_name) {
                        if ($locationText) $locationText .= '<br/>';
                        $locationText = $city_name;
                    }
                    $state_name = '';
                    if ($hotels['hrhws_state'] != '') {
                        $state_name = $this->utils->htmlEntityDecode($hotels['hrhws_state']);
                        if ($city_name == '') $locationText .= '<br/>';
                        $locationText .= ', ' . $state_name;
                    }
                    $country_name = $this->utils->htmlEntityDecode($hotels['hrhc_country']);
                    if ($country_name != '') {
                        if ($city_name == '' && $state_name == '') $locationText .= '<br/>';
                        $locationText .= ', ' . $country_name;
                    }
                }
                $cityName = $locationText;
                $dimagepath = 'media/images/';
                $dimage = 'hotel-icon-image2.jpg';
                if ($hotels['hrhi_filename'] != '') {
                    $dimagepath = 'media/hotels/' . $hotels['hrh_id'] . '/' . $hotels['hrhi_location'] . '/';
                    $dimage = $hotels['hrhi_filename'];
                }
                $bag = $item_id;
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/placestostay_icon.png');
                if ($hotels['hr_reference'] != '') {
                    $eachtypetext = 'hotels-reserved';
                    if (strlen($bg_name) > 70) {
                        $bg_name = $this->utils->getMultiByteSubstr($bg_name, 73, NULL, $lang);
                    }
                    $bag_object['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'discover284162');

                    $bag_object['image'] = $bag_object['img'];
                    if ($media_bucket_url != '' && $bag_object['img'] != '') {
                        $explode_array_media = explode($media_bucket_url, $bag_object['img']);
                        $bag_object['image'] = $explode_array_media[1];
                    }
                    if (substr($bag_object['image'], 0, 1) == '/') $bag_object['image'] = ltrim($bag_object['image'], '/');

                    $bag_object['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');

                    if ($stars > 5) $stars = 5;
                    $bag_object['stars'] = $stars;
                    $bag_object['type'] = $eachtypetext;
                    $bag_object['entity_type'] = $eachtype;
                    $bag_object['name'] = $bg_name;
                    $bag_object['namealt'] = $bg_namealt;
                    $bag_object['entityId'] = $bag_object['bag'] = $bag;
                    $bag_object['id'] = $item['b_id'];
                    $bag_object['icon'] = $icon;
                    $bag_object['link'] = $page_link;
                    $bag_object['address'] = $cityName;
                    $bag_object['latitude'] = $lat;
                    $bag_object['logitude'] = $log;

                    $bag_object['checkIndate'] = date("d", strtotime($hotels['hr_fromDate']));
                    $bag_object['checkOutdate'] = date("d", strtotime($hotels['hr_toDate']));
                    $bag_object['checkIn'] = date("M Y l", strtotime($hotels['hr_fromDate']));
                    $bag_object['checkOut'] = date("M Y l", strtotime($hotels['hr_toDate']));
                    $bag_object['reference'] = $hotels['hr_reference'];
                    $bag_object['linkReservation'] = $this->container->get('TTRouteUtils')->returnBookingDetailsLink($lang, $hotels['hr_reference']);
                    $poi_Array[] = $bag_object;

                    continue;
                }
            } else if ($eachtype == $this->container->getParameter('SOCIAL_ENTITY_LANDMARK')) {
                $eachtypetext = 'poi';
                $bag_object['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot_blue.png');
                $poi = $item;
                if ($poi['dp_id'] == '') continue;

                $im_arr = $poi['dpi_filename'];
                $media_poi['img'] = '';
                if (sizeof($im_arr)) {
                    $media_poi['img'] = $im_arr;
                }

                $log = $poi['dp_longitude'];
                $lat = $poi['dp_latitude'];
                $page_link  = $this->container->get('TTRouteUtils')->returnThingstodoReviewLink($lang, $poi['dp_id'], $this->utils->htmlEntityDecode($poi['dp_name']));
                $bg_name    = $this->utils->htmlEntityDecode($poi['dp_name']);
                $bg_namealt = $this->utils->cleanTitleDataAlt($poi['dp_name']);

                if ($poi['dptd_image'] != '') {
                    $dimagepath = "media/thingstodo/";
                    $dimage = $poi['dptd_image'];
                } else if ($media_poi['img']) {
                    $dimagepath = 'media/discover/';
                    $dimage = $media_poi['img'];
                } else {
                    $dimagepath = 'media/images/';
                    $dimage = 'landmark-icon2.jpg';
                }

                if ($poi['dpw_city'] != '') {
                    $city_name = $this->utils->htmlEntityDecode($poi['dpw_city']);
                    if ($city_name != '') {
                        $locationText .= $city_name;
                    }
                    if ($poi['dps_state'] != '') {
                        $state_name = $this->utils->htmlEntityDecode($poi['dps_state']);
                        $locationText .= ', ' . $state_name;
                    }
                    $country_name = $this->utils->htmlEntityDecode($poi['dpc_country']);
                    if ($country_name != '') {
                        $locationText .= ', ' . $country_name;
                    }
                } else {
                    $locationText = $poi['dp_address'];
                }
                if ($locationText == '') {
                    $locationText = $poi['dp_address'];
                } else if ($poi['dp_address'] != '') {
                    $locationText .= ' - ' . $poi['dp_address'];
                }
                $cityName = $locationText;
                $bag = $item_id;
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/thingstodo-icon.png');
            } else if ($eachtype == $this->container->getParameter('SOCIAL_ENTITY_AIRPORT')) {
                $eachtypetext = 'airport';
                $bag_object['markerImage'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_empty.png');
                $poi = $item;
                if ($poi['ar_id'] == '') continue;

                $media_poi['img'] = $poi['ari_filename'];

                $log = $poi['ar_longitude'];
                $lat = $poi['ar_latitude'];
                $page_link  = $this->container->get('TTRouteUtils')->returnAirportReviewLink($lang, $poi['ar_id'], $this->utils->htmlEntityDecode($poi['ar_name']));
                $bg_name    = $this->utils->htmlEntityDecode($poi['ar_name']);
                $bg_namealt = $this->utils->cleanTitleDataAlt($poi['ar_name']);

                if ($media_poi['img']) {
                    $dimagepath = 'media/discover/';
                    $dimage = $media_poi['img'];
                } else {
                    $dimagepath = 'media/images/';
                    $dimage = 'airport-icon.jpg';
                }

                if ($poi['arw_city'] != '') {
                    $city_name = $this->utils->htmlEntityDecode($poi['arw_city']);
                    if ($city_name != '') {
                        $locationText .= $city_name;
                    }
                    if ($poi['ars_state'] != '') {
                        $state_name = $this->utils->htmlEntityDecode($poi['ars_state']);
                        $locationText .= ', ' . $state_name;
                    }
                    $country_name = $this->utils->htmlEntityDecode($poi['arc_country']);
                    if ($country_name != '') {
                        $locationText .= ', ' . $country_name;
                    }
                }
                if ($locationText == '') {
                    $locationText = $poi['ar_city'];
                }
                $cityName = $locationText;
                $bag = $item_id;
                $icon = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/thingstodo-icon.png');
            } else {
                continue;
            }
            if (strlen($bg_name) > 70) {
                $bg_name = $this->utils->getMultiByteSubstr($bg_name, 73, NULL, $lang);
            }
            if ($dimagepath) {
                $bag_object['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'discover284162');
            } else {
                $bag_object['img'] = $dimage;
            }

            $bag_object['image'] = $bag_object['img'];
            if ($media_bucket_url != '' && $bag_object['img'] != '') {
                $explode_array_media = explode($media_bucket_url, $bag_object['img']);
                $bag_object['image'] = $explode_array_media[1];
            }
            if (substr($bag_object['image'], 0, 1) == '/') $bag_object['image'] = ltrim($bag_object['image'], '/');

            $bag_object['id'] = $item['b_id'];
            $bag_object['entity_id'] = $bag_object['entityId'] = $bag_object['bag'] = $bag;
            $bag_object['entity_type'] = $eachtype;
            $bag_object['type'] = $eachtypetext;
            $bag_object['name'] = $bg_name;
            $bag_object['namealt'] = $bg_namealt;
            $bag_object['entity_name_clean'] = str_replace('-', '+', $bg_namealt);

            if ($stars > 5) $stars = 5;
            $bag_object['stars'] = $stars;
            $bag_object['checkIn'] = $checkIn;
            $bag_object['checkIndate'] = '';
            $bag_object['checkOut'] = $checkOut;
            $bag_object['checkOutdate'] = '';
            $bag_object['linkReservation'] = $linkReservation;
            $bag_object['reference'] = '';
            $bag_object['icon'] = $icon;

            $bag_object['m_icon'] = $bag_object['icon'];
            if ($media_bucket_url != '' && $bag_object['icon'] != '') {
                $explode_array_media = explode($media_bucket_url, $bag_object['icon']);
                $bag_object['m_icon'] = $explode_array_media[1];
            }
            if (substr($bag_object['m_icon'], 0, 1) == '/') $bag_object['m_icon'] = ltrim($bag_object['m_icon'], '/');

            $bag_object['link'] = $page_link;
            $bag_object['address'] = $cityName;
            $bag_object['latitude'] = $lat;
            $bag_object['logitude'] = $log;
            $poi_Array[] = $bag_object;
        }
        return $poi_Array;
    }

    /*
     * This method handles get bag info
     *
     * @param bagId
     *
     * @return baginfo array
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getBagInfo($bagId)
    {
        return $this->em->getRepository('TTBundle:CmsBag')->getBagInfo($bagId);
    }

    /*
     * Adding a new bag
     *
     * @param userId
     * @param name
     *
     * @return bagId
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function addBagNew($userId, $name, $params = null)
    {
        $insert = $this->em->getRepository('TTBundle:CmsBag')->addBagNew($userId, $name, $params);

        $return = array();
        if ($insert) {
            $return['success'] = true;
            $return['status'] = 'ok';
            $return['name'] = $name;
            $return['id'] = $insert;
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }

        return $return;
    }

    /*
     * Adding a new bag item
     *
     * @param userId
     * @param type
     * @param bag_id
     * @param item_id
     *
     * @return bagitemId
    */
    public function addBagItemNew($params)
    {
        $insert = $this->em->getRepository('TTBundle:CmsBagitem')->addBagItemNew($params);

        $return = array();
        if ($insert) {
            $return['success'] = true;
            $return['status'] = 'ok';
            $return['id'] = $insert;
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }

        return $return;
    }

    /*
     * Edit Bag information
     *
     * @param array params
     *
     * @return bool
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function editBagInfo($params = array())
    {
        if (empty($params)) {
            return false;
        }

        $update = $this->em->getRepository('TTBundle:CmsBag')->editBagInfo($params);

        $return = array();
        if ($update) {
            $return['success'] = true;
            $return['status'] = 'ok';
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }

        return $return;
    }

    /*
     * Delete Bag
     *
     * @param id
     *
     * @return bool
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteBag($id, $user_id)
    {
        $delete = $this->em->getRepository('TTBundle:CmsBag')->deleteBag($id, $user_id, $this->container->getParameter('SOCIAL_ENTITY_BAG'));

        $return = array();
        if ($delete) {
            $return['success'] = true;
            $return['status'] = 'ok';
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }

        return $return;
    }

    /*
     * get Bag Item Info
     *
     * @param integer $id
     * @param intefer $userId
     *
     * @return array
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getBagItemInfo($id, $userId)
    {
        $info = $this->em->getRepository('TTBundle:CmsBagitem')->getBagItemInfo($id);

        $return = array();
        if (!$info || $userId != $info['cbi_userId']) {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        } else {
            $return['success'] = true;
            $return['data'] = $info;
            $return['status'] = 'ok';
            $bagList = $this->getUserBagList($userId);
            $return['data']['baglist'] = $bagList['data'];
        }

        return $return;
    }

    /**
     * Update bag item for New Design
     *
     */
    public function updateBagItemInfo($params)
    {
        if (empty($params)) {
            return false;
        }

        $update = $this->em->getRepository('TTBundle:CmsBag')->updateBagItemInfo($params);

        $return = array();
        if ($update) {
            $return['success'] = true;
            $return['status'] = 'ok';
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }

        return $return;
    }

    /**
     * Delete bag item for New Design
     *
     */
    public function deleteBagItem($id, $userId)
    {
        $delete = $this->em->getRepository('TTBundle:CmsBagitem')->deleteBagItem($id, $userId);

        $return = array();
        if ($delete) {
            $return['success'] = true;
            $return['status'] = 'ok';
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
        }

        return $return;
    }

    /**
     * Gets report reason list for a givent entity type
     * @param integer $entity_type the desired entity type
     * 14_0 delete channel reason
     * 2_0 report a tuber
     * 2_1 report a tuber content
     * @return array
     */
    public function getReportReasonList($srch_options)
    {
        return $this->em->getRepository('TTBundle:CmsReportReason')->getReportReasonList($srch_options);
    }

    /**
     * add report for a givent entity type
     * @return integer | false the newly inserted cms_report id or false if not inserted
     */
    public function addReportData($options)
    {
        return $this->em->getRepository('TTBundle:CmsReportReason')->addReportData($options);
    }



    public function userLogout($tokenid)
    {
        return $this->em->getRepository('TTBundle:CmsTubers')->endingSession($tokenid);

    }
    /**
     * gets a user login credentials
     * @param string $username
     * @param string $pswd
     * @param integer $client_type web,android,iphone so far
     * @return array | false cms_users record if login was ok or false if invalid login
     */
    function userLoginFacebook($fb_user) {

        return $this->em->getRepository('TTBundle:CmsUsers')->userLoginFacebook($fb_user);
    }
}
