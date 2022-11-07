<?php

namespace RestBundle\Controller\users;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Form\Form;
use DealBundle\Model\DealAirport;
use RestBundle\Controller\TTRestController;
use RestBundle\Services\OauthHelperServices;

class UsersController extends TTRestController
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->request = Request::createFromGlobals();
        $this->utils = $this->get('app.utils');
    }

    /**
     * Sample get error
     *
     */
    public function getUserErrorAction()
    {
        $this->isRequestValid('GET');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //emptying user array so it would return an error
        $user = array();
        if (empty($user)) {
            throw new HttpException(400, "User Not Found");
        }
    }

    /**
     * Sample success of get method
     *
     */
    public function getUserAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $data = array();
        $data['fullname'] = $user->getFullname();
        $data['username'] = $user->getYourusername();
        $data['userId'] = $user->getId();
        $data['isCorporateAccount'] = $user->getIsCorporateAccount();
        $data['published'] = $user->getPublished();

        $response = new Response(json_encode($data));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * QUERY STRING implementation
     *
     * @QueryParam(name="id", requirements="\d+", strict=true, nullable=true, description="id of user")
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     */
    public function viewUserAction(ParamFetcher $paramFetcher)
    {
        $userId = 0;
        if ($paramFetcher->get('id')) {
            $userId = $paramFetcher->get('id');
        }

        $userInfo = $this->get('UserServices')->getUserInfoById($userId);
        if (empty($userInfo)) {
            throw new HttpException(400, "Invalid user credentials. Please try again.");
        }

        //for now just json response
        $response = new Response(json_encode($userInfo));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Updating of username based on the username being passed
     *
     * @RequestParam(name="newUserName", requirements="[a-z]+", description="new user name.")
     * @RequestParam(name="userId", requirements="\d+", description="user id")
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     *
     */
    public function cpostAction(ParamFetcher $paramFetcher)
    {
        $post = $paramFetcher->all();

        //just a simple updating of username
        $userInfo = $this->get('UserServices')->getUserInfoById($post['userId']);
        $olduserName = $userInfo['cu_yourusername'];
        $success = true;
        $result = array();

        if (empty($userInfo)) {
            throw new HttpException(400, "Invalid user credentials. Please try again.");
        } else {
            //check first if username is unique and password is valid
            if (isset($post['newUserName']) && $post['newUserName'] && $olduserName != $post['newUserName'] && $this->get('UserServices')->checkDuplicateUserName($post['userId'], $post['newUserName'])) {
                $suggestedNewUserNames = $this->get('UserServices')->suggestUserNameNew($post['newUserName']);
                $message = $this->translator->trans('Your username is already taken. try:') . ' ' . implode(' or ', $suggestedNewUserNames) . '.';
                $success = false;
                $statusCode = 400;
            }

            if ($success) {
                $modify = $this->get('UserServices')->modifyUser(array('id' => $post['userId'], 'yourUserName' => $post['newUserName']));
                $message = $this->translator->trans('Username has been successfully updated!');
                $statusCode = 200;
            }
        }

        $result['success'] = $success;
        $result['message'] = $message;

        $response = new Response(json_encode($result));
        $response->setStatusCode($statusCode);
        return $response;
    }

    /**
     * @Route(path="/api/cput",name="_fos_cput", methods={"PUT"})
     *
     * @QueryParam(name="id", requirements="\d+", default="1", description="userId")
     * @QueryParam(name="lname", requirements="[a-z]+", description="lastname")
     * @QueryParam(name="fname", requirements="[a-z]+", description="firstname")
     * @param ParamFetcher $paramFetcher
     * @return Response
     *
     */
    public function cputAction(ParamFetcher $paramFetcher)
    {
        $userInfo = $this->get('UserServices')->getUserInfoById($paramFetcher->get('id'));

        if (empty($userInfo)) {
            throw new HttpException(400, "Invalid user credentials. Please try again.");
        }

        $param = array();
        $param['id'] = $paramFetcher->get('id');
        $param['lname'] = $paramFetcher->get('lname');
        $param['fname'] = $paramFetcher->get('fname');

        $modify = $this->get('UserServices')->modifyUser($param);
        if ($modify) {
            $message = $this->translator->trans('User information has been successfully updated!');
            $statusCode = 200;
            $success = true;
        } else {
            $message = $this->translator->trans('Error in updating.!');
            $statusCode = 400;
            $success = false;
        }

        $result = array();
        $result['success'] = $success;
        $result['message'] = $message;

        $response = new Response(json_encode($result));
        $response->setStatusCode($statusCode);
        return $response;
    }

    /**
     * Method DELETE
     * @param Request $request
     * @param integer $id userId
     * @Rest\View(statusCode=201)
     */
    public function deleteUserAction($userId)
    {
        $userInfo = $this->get('UserServices')->getUserInfoById($userId);
        if (empty($userInfo)) {
            throw new HttpException(400, "Invalid user credentials. Please try again.");
        }

        $success = $this->get('UserServices')->deleteUser($userId);
        if ($success) {
            $message = $this->translator->trans('User has been successfully deleted!');
        } else {
            throw new HttpException(422, $this->translator->trans('Error in deleting.!'));
        }

        $result = array();
        $result['status'] = "success";
        $result['message'] = $message;

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Getting Booking Details
     *
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     *
     */
    public function corporateSigninAction()
    {
        // specify required fields
        $requirements = array(
            'username',
            'password',
            'client',
            'client_id',
            'client_secret',
            'grant_type'
        );

        // fech post json data
        $params = $this->fetchRequestData($requirements);

        $client = $params['client'];
        $params['clientType'] = 0;
        // @TODO Add these as constants in constant yml file

        // web client

        //define('CLIENT_WEB', 0);

        //iphone client

        //define('CLIENT_IOS', 1);

        // android client

        //define('CLIENT_ANDROID', 2);

        // android client

        //define('CLIENT_WINDOWS', 3);

        // android client

        //define('CLIENT_NOKIA', 4);

        // android client

        //define('CLIENT_BLACKBERRY', 5);
        switch ($client) {
            case "ANDROID":
                $params['clientType'] = 2;
                break;
            case "IOS":
                $params['clientType'] = 1;
                break;
            case "WINDOWS":
                $params['clientType'] = 3;
                break;
            case "BLACKBERRY":
                $params['clientType'] = 5;
                break;
            case "NOKIA":
                $params['clientType'] = 4;
                break;
        }

        //validate first for invalid password
        $validatePass = $this->get('UserServices')->validateUserLengthPassword($params['password']);

        if (isset($validatePass['success']) && $validatePass['success'] == false) {
            throw new HttpException(422, $validatePass['message']);
        }

        if (($userRec = $this->get('UserServices')->userLogin($params))) {
            $invalid = false;
            if (isset($params['model_number'])) {
                $paramsTrack['ipAddress'] = (isset($params['REMOTE_ADDR']) && !empty($params['REMOTE_ADDR'])) ? $params['REMOTE_ADDR'] : '';

                $paramsTrack['forwardedIpAddress'] = (isset($params['HTTP_X_FORWARDED_FOR']) && !empty($params['HTTP_X_FORWARDED_FOR'])) ? $params['HTTP_X_FORWARDED_FOR'] : '';
                $paramsTrack['userAgent'] = $params['model_number'];
                $paramsTrack['userId'] = $userRec['row']['cc_id'];
                $this->get('UserServices')->userLoginTrack($paramsTrack);
            }
        } else {
            $invalid = true;
        }

        $userInfo = array();
        if ($invalid) {
            throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));
        } else {
            $userInfo['status'] = 'success';
            $userInfo['ssid'] = $userRec['token'];
            $userInfo['username'] = $userRec['row']['cc_yourusername'];
            $userInfo['fname'] = $userRec['row']['cc_fname'];
            $userInfo['lname'] = $userRec['row']['cc_lname'];
            $directory = '/' . $this->container->getParameter('USER_AVATAR_RELATIVE_PATH');
            $profilePic = $userRec['row']['cc_profilePic'];
            if ($profilePic == '') {
                $profilePic = 'he.jpg';
                if ($userRec['row']['cc_gender'] == 'F') {
                    $profilePic = 'she.jpg';
                }
            }
            $avatar = $directory . $profilePic;
            $userInfo['avatar'] = $avatar;

            if ($userRec['row']['cc_displayFullname'] == 1) {
                $fullname = $userInfo['fname'] . " " . $userInfo['lname'];
                if (strlen($fullname) <= 1) {
                    $fullname = $userRec['row']['cc_yourusername'];
                }
            } else {
                $fullname = $userRec['row']['cc_yourusername'];
            }

            $userInfo['fullname'] = $fullname;
            $userInfo['email'] = $userRec['row']['cc_youremail'];
            $userInfo['userid'] = $userRec['row']['cc_id'];

            //inserting to cms_tubers
            $val = array(
                'userId' => $userInfo['userid'],
                'longitude' => isset($params['long']) ? $params['long'] : 0,
                'latitude' => isset($params['lat']) ? $params['lat'] : 0,
                'ipAddress' => $this->request->server->get('REMOTE_ADDR', ''),
                'forwardedIpAddress' => $this->request->server->get('HTTP_X_FORWARDED_FOR', ''),
                'userAgent' => $this->request->server->get('HTTP_USER_AGENT', ''),
                'uid' => $userInfo['ssid'],
                'clientType' => $params['clientType'],
                'socialToken' => '',
                'keepMeLogged' => 1
            );
            $this->get('UserServices')->userToSession($val);
        }

        $request = Request::createFromGlobals();
        $request->request->set('client_id', $params['client_id']);
        $request->request->set('client_secret', $params['client_secret']);
        $request->request->set('grant_type', $params['grant_type']);
        $request->request->set('username', $params['username']);
        $request->request->set('password', $params['password']);

        $tokenDetails = $this->get('OauthHelperServices')->getTokenInfo($request);
        $tokenDetailsEncoded = $tokenDetails->getContent();
        $tokenDetailsDeco = json_decode($tokenDetailsEncoded, TRUE);

        if (isset($tokenDetailsDeco['error']) && $tokenDetailsDeco['error']) {
            throw new HttpException(400, $tokenDetailsDeco['error_description']);
        }

        $userInfo['token'] = $tokenDetailsDeco;
        $userInfo['account'] = array('account_id' => $userRec['row']['cc_corpoAccountId'], 'account_name' => $userRec['row']['ca_name']);

        //updating cms_mobile_token table
        $val['tokenId'] = (isset($params['registration_id']) && $params['registration_id']) ? $params['registration_id'] : '';
        $val['platform'] = $params['clientType'];
        $val['aptType'] = $client;
        $this->get('UserServices')->userToMobileToken($val);

        //updating oauth token table
        if (isset($params['device_information']) && $params['device_information']) {
            $val['accessToken'] = $userInfo['token']['access_token'];
            $val['deviceInformation'] = json_encode($params['device_information']);
            $this->get('UserServices')->updateOauthAccessToken($val);
        }


        $response = new Response(json_encode($userInfo));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Getting User Info
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     */
    public function usersSignInAction()
    {

        // specify required fields
        $requirements = array(
            'username',
            'password',
            'client',
            'client_id',
            'client_secret',
            'grant_type'
        );

        // fech post json data
        $params = $this->fetchRequestData($requirements);

        $client = $params['client'];
        $params['clientType'] = 0;
        // @TODO Add these as constants in constant yml file

        // web client

        //define('CLIENT_WEB', 0);

        //iphone client

        //define('CLIENT_IOS', 1);

        // android client

        //define('CLIENT_ANDROID', 2);

        // android client

        //define('CLIENT_WINDOWS', 3);

        // android client

        //define('CLIENT_NOKIA', 4);

        // android client

        //define('CLIENT_BLACKBERRY', 5);
        switch ($client) {
            case "ANDROID":
                $params['clientType'] = 2;
                break;
            case "IOS":
                $params['clientType'] = 1;
                break;
            case "WINDOWS":
                $params['clientType'] = 3;
                break;
            case "BLACKBERRY":
                $params['clientType'] = 5;
                break;
            case "NOKIA":
                $params['clientType'] = 4;
                break;
        }

        //validate first for invalid password
        $validatePass = $this->get('UserServices')->validateUserLengthPassword($params['password']);

        if (isset($validatePass['success']) && $validatePass['success'] == false) {

            $response = new Response(json_encode(array('code'=>422,'message'=>$validatePass['message'])));
            $response->setStatusCode(422);
            return $response;
        }

        if (($userRec = $this->get('UserServices')->userLogin($params))) {

            $invalid = false;
            if (isset($params['model_number'])) {
                $paramsTrack['ipAddress'] = (isset($params['REMOTE_ADDR']) && !empty($params['REMOTE_ADDR'])) ? $params['REMOTE_ADDR'] : '';

                $paramsTrack['forwardedIpAddress'] = (isset($params['HTTP_X_FORWARDED_FOR']) && !empty($params['HTTP_X_FORWARDED_FOR'])) ? $params['HTTP_X_FORWARDED_FOR'] : '';
                $paramsTrack['userAgent'] = $params['model_number'];
                $paramsTrack['userId'] = $userRec['row']['cc_id'];
                $this->get('UserServices')->userLoginTrack($paramsTrack);
            }
        } else {

            $invalid = true;
        }

        $userInfo = array();
        if ($invalid) {

            $response = new Response(json_encode(array('code'=>500,'message'=>$this->translator->trans('Invalid user credentials. Please try again.'))));
            $response->setStatusCode(500);
            return $response;
        } else {
            $userInfo['status'] = 'success';
            $userInfo['ssid'] = $userRec['token'];
            $userInfo['username'] = $userRec['row']['cc_yourusername'];
            $userInfo['countryCode'] = ( isset($userRec['row']['cc_yourcountry']) && $userRec['row']['cc_yourcountry'] != NULL )?$userRec['row']['cc_yourcountry']:'';
            $userInfo['fname'] = $userRec['row']['cc_fname'];
            $userInfo['lname'] = $userRec['row']['cc_lname'];
            $directory = '/' . $this->container->getParameter('USER_AVATAR_RELATIVE_PATH');
            $profilePic = $userRec['row']['cc_profilePic'];
            if ($profilePic == '') {
                $profilePic = 'he.jpg';
                if ($userRec['row']['cc_gender'] == 'F') {
                    $profilePic = 'she.jpg';
                }
            }
            $avatar = $directory . $profilePic;
            $userInfo['avatar'] = $avatar;

            if ($userRec['row']['cc_displayFullname'] == 1) {
                $fullname = $userInfo['fname'] . " " . $userInfo['lname'];
                if (strlen($fullname) <= 1) {
                    $fullname = $userRec['row']['cc_yourusername'];
                }
            } else {
                $fullname = $userRec['row']['cc_yourusername'];
            }

            $userInfo['fullname'] = $fullname;
            $userInfo['email'] = $userRec['row']['cc_youremail'];
            $userInfo['userid'] = $userRec['row']['cc_id'];

            //inserting to cms_tubers
            $val = array(
                'userId' => $userInfo['userid'],
                'longitude' => isset($params['long']) ? $params['long'] : 0,
                'latitude' => isset($params['lat']) ? $params['lat'] : 0,
                'ipAddress' => $this->request->server->get('REMOTE_ADDR', ''),
                'forwardedIpAddress' => $this->request->server->get('HTTP_X_FORWARDED_FOR', ''),
                'userAgent' => $this->request->server->get('HTTP_USER_AGENT', ''),
                'uid' => $userInfo['ssid'],
                'clientType' => $params['clientType'],
                'socialToken' => '',
                'keepMeLogged' => 1
            );
            $this->get('UserServices')->userToSession($val);
        }

        $request = Request::createFromGlobals();
        $request->request->set('client_id', $params['client_id']);
        $request->request->set('client_secret', $params['client_secret']);
        $request->request->set('grant_type', $params['grant_type']);
        $request->request->set('username', $params['username']);
        $request->request->set('password', $params['password']);


        $tokenDetails = $this->get('OauthHelperServices')->getTokenInfo($request);
        $tokenDetailsEncoded = $tokenDetails->getContent();
        $tokenDetailsDeco = json_decode($tokenDetailsEncoded, TRUE);

        if (isset($tokenDetailsDeco['error']) && $tokenDetailsDeco['error']) {

            $response = new Response(json_encode(array('code'=>500,'message'=>$tokenDetailsDeco['error_description'] )));
            $response->setStatusCode(400);
            return $response;
        }

        $userInfo['token'] = $tokenDetailsDeco;
        $userInfo['account'] = array('account_id' => $userRec['row']['cc_corpoAccountId'], 'account_name' => $userRec['row']['ca_name']);

        //updating cms_mobile_token table
        $val['tokenId'] = (isset($params['registration_id']) && $params['registration_id']) ? $params['registration_id'] : '';
        $val['platform'] = $params['clientType'];
        $val['aptType'] = $client;
        $this->get('UserServices')->userToMobileToken($val);

        //updating oauth token table
        if (isset($params['device_information']) && $params['device_information']) {
            $val['accessToken'] = $userInfo['token']['access_token'];
            $val['deviceInformation'] = json_encode($params['device_information']);
            $this->get('UserServices')->updateOauthAccessToken($val);
        }
        $response = new Response(json_encode($userInfo));
        $response->setStatusCode(200);
        return $response;

    }

    /**
     * Method POST
     * Getting User Info
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     */
    public function facebookSignInAction()
    {
        // specify required fields
        $requirements = array(
            'access_token',
            'client_id',
            'client_secret',
            'grant_type'
        );

        // fech post json data
        $params = $this->fetchRequestData($requirements);
        $client = $params['client'];
        $client_id = $params['client_id'];
        $client_secret = $params['client_secret'];
        $grant_type = $params['grant_type'];

        switch ($client) {
            case "ANDROID":
                $params['clientType'] = 2;
                break;
            case "IOS":
                $params['clientType'] = 1;
                break;
            case "WINDOWS":
                $params['clientType'] = 3;
                break;
            case "BLACKBERRY":
                $params['clientType'] = 5;
                break;
            case "NOKIA":
                $params['clientType'] = 4;
                break;
        }

        if (isset($params['client_type'])) {
            $client_type = $params['client_type'];
        } else {
            $client_type = 'ANDROID';
        }
        $access_token = $params['access_token'];

        $Result = array();


        $config_use = array(
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'appId' => $this->container->getParameter('facebook_app_id'),
            'app_secret' => $this->container->getParameter('facebook_app_secret'),
            'secret' => $this->container->getParameter('facebook_app_secret'),
            'default_graph_version' => $this->container->getParameter('facebook_default_graph_version')
        );


        try {
            $uricurserver = $this->get('TTRouteUtils')->currentServerURL();
            $fb = new \Facebook\Facebook($config_use);
            $response = $fb->get('/me?fields=id,name,email,birthday,first_name,last_name', $access_token);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error

            $Result['status'] = 'error';
            $Result['msg'] = 'Graph returned an error: ' . $e->getMessage();

        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            $Result['status'] = 'error';
            $Result['msg'] = 'Facebook SDK returned an error: ' . $e->getMessage();

        }

        $user_profile = $response->getGraphUser();
        if (!isset($user_profile['email']) || is_null($user_profile['email']) || $user_profile['email'] == '') {

            $Result['status'] = 'error';
            $Result['msg'] = 'Facebook SDK returned an error: Invalid Facebook account';

        }

        $user_email = $user_profile['email'];
        $check_email = $this->get('UserServices')->getUserDetails(array('yourEmail' => $user_email));

        if (isset($user_profile['birthday'])) {
            $birthday = $user_profile['birthday'];
            $birthday_str = $birthday->format('Y-m-d');
        } else {
            $birthday_str = null;
        }

        if ($check_email && isset($check_email[0])) {
            $res = $this->get('UserServices')->userUpdateFbUser($check_email[0]['cu_id'], $user_profile['id'], $user_profile['id']);
        } else {
            $post['fullName'] = $user_profile['name'];
            $post['yourEmail'] = $user_profile['email'];
            $post['fname'] = $user_profile['first_name'];
            $post['lname'] = $user_profile['last_name'];
            $post['yourBday'] = $birthday_str;
            $post['fb_token'] = $access_token;
            $post['fb_user'] = $user_profile['id'];
            $post['password'] = $user_profile['id'];
            $post['yourPassword'] = $user_profile['id'];
            $post['gender'] = 'O';
            $post['yourUserName'] = $user_profile['email'];
            $post['defaultPublished'] = 1;
            $post['cmsUserGroupId'] = $this->container->getParameter('ROLE_USER');

            $res = $this->get('UserServices')->generateUser($post);
        }

        $userDetails = $this->get('UserServices')->userLoginFacebook($user_profile['id']);
        $secretKey = "fakesecretkey";
        if ($userDetails) {
            $Result['status'] = 'success';
            $Result['username'] = $user_profile['first_name'] . ' ' . $user_profile['last_name'];
            $Result['countryCode'] = ( isset($userDetails[0]['YourCountry']) && $userDetails[0]['YourCountry'] != NULL )?$userDetails[0]['YourCountry']:'';
            $Result['fname'] = $user_profile['first_name'];
            $Result['lname'] = $user_profile['last_name'];
            $Result['fullname'] = $userDetails[0]['FullName'];
            $Result['email'] = $user_profile['email'];
            $Result['userid'] = (int)$userDetails[0]['id'];
            $data = time() . "_" .$Result['userid'];
            $token = hash('sha512', $secretKey . $data);
            $Result['ssid'] = $token;
            $directory = '/' . $this->container->getParameter('USER_AVATAR_RELATIVE_PATH');
            $profilePic = $userDetails[0]['profile_Pic'];
            if ($profilePic == '') {
                $profilePic = 'he.jpg';
                if ($userDetails[0]['gender'] == 'F') {
                    $profilePic = 'she.jpg';
                }
            }

            $avatar = $directory . $profilePic;
            $Result['avatar'] = $avatar;

            $request = Request::createFromGlobals();
            $request->request->set('client_id', $client_id);
            $request->request->set('client_secret', $client_secret);
            $request->request->set('grant_type', $grant_type);
            $request->request->set('username', $user_profile['first_name'] . ' ' . $user_profile['last_name']);
            $request->request->set('password', $user_profile['id']);

            $params['username'] = $user_profile['email'];
            $params['password'] = $user_profile['id'];

            $userRec = $this->get('UserServices')->userLogin($params);


                $req=new \Symfony\Component\HttpFoundation\Request($params);
                $tokenDetails = $this->get('OauthHelperServices')->getTokenInfo($req);
                $tokenDetailsEncoded = $tokenDetails->getContent();
                $tokenDetailsDeco = json_decode($tokenDetailsEncoded, TRUE);

                            if (isset($tokenDetailsDeco['error']) && $tokenDetailsDeco['error']) {
                                throw new HttpException(400, $tokenDetailsDeco['error_description']);
                            }

                            $Result['token'] = $tokenDetailsDeco;

                                  //updating cms_mobile_token table
                                  $val['tokenId'] = (isset($params['registration_id']) && $params['registration_id']) ? $params['registration_id'] : '';
                                  $val['platform'] = $params['clientType'];
                                  $val['aptType'] = $client;
                                  $this->get('UserServices')->userToMobileToken($val);

                                  //updating oauth token table
                                  if (isset($params['device_information']) && $params['device_information']) {

                                      $val['accessToken'] = $Result['token']['access_token'];
                                      $val['deviceInformation'] = json_encode($params['device_information']);
                                      $this->get('UserServices')->updateOauthAccessToken($val);

                                  }

        } else {
            $Result['status'] = 'error';
            $Result['msg'] = 'not able to register user';
        }

        $response = new Response(json_encode($Result));
        $response->setStatusCode(200);
        return $response;
    }


    /**
     * Method POST
     * Sign out
     *
     * @return Response
     */
    public function corporateSignoutAction(Request $request)
    {
        // specify required fields
        $requirements = array(
            'tokenid',
            'refreshid',
            'ssid'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        $tokenObj = $this->get('security.token_storage')->getToken();
        $accessToken = $tokenObj->getToken();

        //delete cms_tubers entry
        $this->get('UserServices')->userEndSession($post['ssid']);
        //delete CmsMobileToken entry
        $this->get('UserServices')->deleteMobileToken($post);
        //delete OauthAccessToken entry
        $this->get('UserServices')->deleteAccessToken($accessToken);
        //delete OauthRefreshToken entry
        $this->get('UserServices')->deleteRefreshToken($post['refreshid']);

        $results = array();
        $results['code'] = 200;
        $results['message'] = $this->translator->trans('Successfully Logout');

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Sign out
     *
     * @return Response
     */
    public function usersSignOutAction(Request $request)
    {

        // specify required fields
        $requirements = array(
            'tokenid',
            'refreshid',
            'ssid'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        $tokenObj = $this->get('security.token_storage')->getToken();
        $accessToken = $tokenObj->getToken();

        //delete cms_tubers entry
        $this->get('UserServices')->userEndSession($post['ssid']);
        //delete CmsMobileToken entry
        $this->get('UserServices')->deleteMobileToken($post);
        //delete OauthAccessToken entry
        $this->get('UserServices')->deleteAccessToken($accessToken);
        //delete OauthRefreshToken entry
        $this->get('UserServices')->deleteRefreshToken($post['refreshid']);

        $results = array();
        $results['code'] = 200;
        $results['message'] = $this->translator->trans('Successfully Logout');

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Get the menu items allowed to be viewed by the logged in user
     *
     * @param $userId
     * @return JSON data object with the needed menu info
     */
    public function getUserMenusAction($userId = 0)
    {
        $userInfo = $this->get('UserServices')->getUserInfoById($userId);
        $results = array();

        if (empty($userInfo)) {
            throw new HttpException(400, $this->translator->trans('Invalid userId'));
        }

        $roleId = $userInfo['cu_cmsUserGroupId']; //PK of cms_user_group

        $menuItems = $this->get('CorpoAdminServices')->getMenuItemsByRole($roleId);
        foreach ($menuItems AS $menuItem) {
            $results[] = $menuItem->toArray();
        }

        $response = new Response(json_encode($results));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Register or creating New user
     *
     * @return response
     *
     */
    public function registerAction()
    {
        // specify required fields
        $requirements = array(
            'userName',
            'password',
            'email'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        //we need to match what's in the database
        $fields = array(
            'yourEmail' => 'email',
            'yourUserName' => 'userName',
            'yourPassword' => 'password',
            'fname' => 'fname',
            'lname' => 'lname',
            'yourIP' => 'ip',
            'yourCountry' => 'countryAbbreviation',
            'yourBday' => 'birthdate',
            'accountId' => 'accountId',
            'gender' => 'gender',
            'cmsUserGroupId' => 'userRole',
            'isChannel' => 'isChannel',
        );

        $params = array();
        $params['userId'] = 0;

        foreach ($fields as $key => $val) {
            if (isset($post[$val]) && $post[$val]) $params[$key] = $post[$val];
        }

        if (isset($params['yourEmail']) && $params['yourEmail']) {
            if ($this->get('UserServices')->checkDuplicateUserEmail($params['userId'], $params['yourEmail'])) {
                throw new HttpException(422, $this->translator->trans('This email address belongs to an existing Tourist Tuber.'));
            }
        }

        if (isset($params['yourUserName']) && $params['yourUserName']) {
            if ($this->get('UserServices')->checkDuplicateUserName($params['userId'], $params['yourUserName'])) {
                $suggestedNewUserNames = $this->get('UserServices')->suggestUserNameNew($params['yourUserName']);
                throw new HttpException(422, $this->translator->trans('Your username is already taken. try:') . ' ' . implode(' or ', $suggestedNewUserNames));
            }
        }

        if (isset($params['yourPassword']) && $params['yourPassword']) {
            //validate first for invalid password
            $validatePass = $this->get('UserServices')->validateUserLengthPassword($params['yourPassword']);
            if (isset($validatePass['success']) && $validatePass['success'] == false) {
                throw new HttpException(422, $validatePass['message']);
            }
        }

        if ((isset($params['fname']) && $params['fname']) || (isset($params['lname']) && $params['lname'])) {
            $fullname = '';
            if ($params['fname']) {
                $fullname .= $params['fname'];
            }
            if ($params['lname']) {
                $fullname .= $params['lname'];
            }
        } else {
            $fullname = $params['fname'] = $params['yourUserName'];
        }

        $params['fullName'] = $fullname;
        $params['defaultPublished'] = 0;
        $result = array();



        $insert = $this->get('UserServices')->generateUser($params);

        if (is_array($insert) && isset($insert['error']) && !empty($insert['error'])) {
            throw new HttpException(422, $insert['error']);
        } else {
            $this->get('UserServices')->sendRegisterActivationEmail(array('userId' => $insert->getId(), 'activationLink' => '/api/users/activate/'));
            $result['status'] = "success";
            $result['message'] = $this->translator->trans('An email is sent to you to activate your account.');
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method PUT
     * Activating newly registered or created user
     *
     * @param $key activation key
     * @return Response
     */
    public function activateAction($key = '')
    {
        if ($key == '') throw new HttpException(400, "Invalid user credentials. Please try again.");

        $userInfo = $this->get('UserServices')->checkUserEmailMD5($key);

        if ( !$userInfo ) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        if ($this->get('UserServices')->activateUsersAccount($userInfo['cu_id'])) {
            $message = $this->translator->trans('Your account is activated.');
        } else {
            throw new HttpException(422, $this->translator->trans('Your activation link has expired.'));
        }

        $result = array();
        $result['status'] = "success";
        $result['message'] = $message;

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method PUT
     * Update user password
     *
     * @QueryParam(name="userId")
     * @QueryParam(name="oldPassword")
     * @QueryParam(name="newPassword")
     * @param ParamFetcher $paramFetcher
     * @Rest\View(statusCode=201)
     */
    public function updatePasswordAction(ParamFetcher $paramFetcher)
    {
        $post = $paramFetcher->all();

        $userInfo = $this->get('UserServices')->getUserInfoById($post['userId']);

        if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        $encodedPass = $userInfo['cu_yourpassword'];
        $saltPass = $userInfo['cu_salt'];

        if (!$this->get('app.sha256salted_encoderservices')->isPasswordValid($encodedPass, $post['oldPassword'], $saltPass)) {
            throw new HttpException(422, $this->translator->trans('Your old password entry not matching current password.'));
        }

        $update = $this->get('UserServices')->UpdateUserPassword($post['userId'], $post['newPassword']);

        if ($update['success'] == false) {
            throw new HttpException(422, $update['message']);
        }

        $result = array();
        $result['status'] = "success";
        $result['message'] = $this->translator->trans('Password has been successfully updated');

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * GET user information
     *
     * @param integer $userId userId
     */
    public function getUserInfoAction($userId)
    {
        $userInfo = $this->get('UserServices')->getUserInfoById($userId, false);

        if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        $data = array();
        $data['fullname'] = $userInfo[0]->getFullname();
        $data['username'] = $userInfo[0]->getYourusername();
        $data['userId'] = $userInfo[0]->getId();
        $data['isCorporateAccount'] = $userInfo[0]->getIsCorporateAccount();
        $data['published'] = $userInfo[0]->getPublished();

        $response = new Response(json_encode($data));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method PUT
     * Update user information
     *
     * @QueryParam(name="email")
     * @QueryParam(name="userName")
     * @QueryParam(name="fname")
     * @QueryParam(name="lname")
     * @QueryParam(name="websiteUrl")
     * @QueryParam(name="smallDescription")
     * @QueryParam(name="birthdate")
     * @QueryParam(name="accountId")
     * @QueryParam(name="gender")
     * @QueryParam(name="userRole")
     *
     * @param integer $id userId
     * @param ParamFetcher $paramFetcher
     */
    public function modifyAction($userId = 0, ParamFetcher $paramFetcher)
    {
        $userInfo = $this->get('UserServices')->getUserInfoById($userId);

        if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        $params = array();
        $params['id'] = $userId;

        //we need to match what's in the database
        $fields = array(
            'yourEmail' => 'email',
            'yourUserName' => 'userName',
            'fname' => 'fname',
            'lname' => 'lname',
            'websiteUrl' => 'websiteUrl',
            'smallDescription' => 'smallDescription',
            'yourBday' => 'birthdate',
            'accountId' => 'accountId',
            'gender' => 'gender',
            'cmsUserGroupId' => 'userRole',
        );

        $post = $paramFetcher->all();

        foreach ($fields as $key => $val) {
            if (isset($post[$val]) && $post[$val]) $params[$key] = $post[$val];
        }

        if (isset($params['yourBday']) && $params['yourBday']) {
            $params['yourBday'] = date("Y-m-d", strtotime($params['yourBday']));
        }

        if (isset($params['yourEmail']) && $params['yourEmail']) {
            if ($this->get('UserServices')->checkDuplicateUserEmail($userId, $params['yourEmail'])) {
                throw new HttpException(422, $this->translator->trans('This email address belongs to an existing Tourist Tuber.'));
            }
        }

        if (isset($params['yourUserName']) && $params['yourUserName']) {
            if ($this->get('UserServices')->checkDuplicateUserName($userId, $params['yourUserName'])) {
                $suggestedNewUserNames = $this->get('UserServices')->suggestUserNameNew($params['yourUserName']);
                throw new HttpException(422, $this->translator->trans('Your username is already taken. try:') . ' ' . implode(' or ', $suggestedNewUserNames));
            }
        }

        $success = $this->get('UserServices')->modifyUser($params);

        if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
            throw new HttpException(422, $success['error']);
        }

        $result = array();
        $result['status'] = "success";
        $result['message'] = $this->translator->trans('User information has been succesfully updated.');

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Reset Password
     *
     * @return response
     *
     */
    public function resetPasswordAction()
    {
        // specify required fields
        $requirements = array(
            'key'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        $result = $params = array();

        $params['email'] = $post['key'];
        $params['notificationTwig'] = 'emails/user-email-forgot-password.html.twig';
        $params['changePassLink'] = '/user/password/forgot/emails/';
        $params['tellUsLink'] = '/user/tell-us/emails/';

        $reset = $this->get('UserServices')->userResetPassword($params);
        if (!$reset) {
            throw new HttpException(422, $this->translator->trans('Invalid credentials. Please try again.'));
        } else {
            $success = (isset($reset['status']) && $reset['status'] == 'ok') ? true : false;
            $message = $this->translator->trans("We've emailed you password reset instructions. Check your email");
        }

        $result['success'] = $success;
        $result['message'] = $message;

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * Tell Us response
     *
     * @param $key key
     * @return Response
     */
    public function tellUsAction($key = '')
    {
        if ($key == '') throw new HttpException(400, "Invalid user credentials. Please try again.");

        $userInfo = $this->get('UserServices')->checkUserEmailMD5($key);

        if ( !$userInfo ) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        $result = array();
        $result['status'] = "success";
        $result['message'] = $this->translator->trans("Thanks for telling us that you did not ask to reset your password. You can access your Tourist Tube account with your current password.");

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method POST
     * Forgot Password
     *
     * @param $key key
     * @return Response
     */
    public function forgotPasswordAction($key = '')
    {
        if ($key == '') throw new HttpException(400, "Invalid user credentials. Please try again.");

        // specify required fields
        $requirements = array(
            'newPassword'
        );

        // fech post json data
        $post = $this->fetchRequestData($requirements);

        $userInfo = $this->get('UserServices')->checkUserEmailMD5($key);

        if ( !$userInfo ) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        $userId = $userInfo['cu_id'];
        $result = $this->get('UserServices')->updateUserPassword($userId, $post['newPassword']);

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Method GET
     * GET profile settings
     *
     * @param integer $userId userId
     * @param integer $accountId accountId
     * @return Response
     */
    public function getProfileSettingsAction($userId, $accountId = 0)
    {
        $parameters['userId'] = $userId;
        $employee = $this->get('CorpoEmployeesServices')->getCorpoAdminEmployeeById($parameters);

        if (!$employee) {
            if (isset($accountId) && $accountId) {
                $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
                if (empty($accountInfo)) {
                    throw new HttpException(422, "Account id invalid.");
                }
                $userInfo = $this->get('UserServices')->getUserInfoById($userId);
                if (empty($userInfo)) {
                    throw new HttpException(422, "User id invalid.");
                }
                $directory = '/' . $this->container->getParameter('USER_AVATAR_RELATIVE_PATH');
                $profilePic = $userInfo['cu_profilePic'];
                if ($profilePic == '') {
                    $profilePic = 'he.jpg';
                    if ($userInfo['cu_gender'] == 'F') {
                        $profilePic = 'she.jpg';
                    }
                }
                $avatar = $directory . $profilePic;
                $return['employee'] = array('id' => '',
                    'account' => array('id' => $accountId, 'name' => $accountInfo['al_name']),
                    'fname' => $userInfo['cu_fname'],
                    'lname' => $userInfo['cu_lname'],
                    'mname' => '',
                    'email' => $userInfo['cu_youremail'],
                    'mobile' => '',
                    'address' => '',
                    'city' => array('id' => '', 'name' => ''),
                    'country' => array('id' => '', 'code' => '', 'name' => ''),
                    'passportNumber' => '',
                    'issueDate' => '',
                    'passportExpiryDate' => '',
                    'avatar' => $avatar
                );
            } else {
                throw new HttpException(401, $this->translator->trans('The user is not authorized to use the API.'));
            }
        } else {
            $return['employee'] = array('id' => $employee['p_id'],
                'account' => array('id' => $employee['p_accountId'], 'name' => $employee['accountName']),
                'fname' => $employee['p_fname'],
                'lname' => $employee['p_lname'],
                'mname' => $employee['p_mname'],
                'email' => $employee['p_email'],
                'mobile' => $employee['p_mobile'],
                'address' => $employee['p_address'],
                'city' => array('id' => $employee['p_cityId'], 'name' => $employee['cityName']),
                'country' => array('id' => $employee['countryId'], 'code' => $employee['countryCode'], 'name' => $employee['countryName']),
                'passportNumber' => $employee['p_passportNumber'],
                'issueDate' => $employee['p_issueDate']->format('Y-m-d H:i:s'),
                'passportExpiryDate' => $employee['p_passportExpiryDate']->format('Y-m-d H:i:s'),
                'avatar' => '/' . $this->getParameter('USER_AVATAR_RELATIVE_PATH') . $employee['p_profilePicture']
            );
        }

        $response = new Response(json_encode($return));
        $response->setStatusCode(200);
        return $response;
    }


    /**
     * Method POST
     * Profile Setting
     *
     * @param integer $userId userId
     * @param integer $accountId accountId
     * @return Response
     */
    public function updateProfileSettingsAction($userId, $accountId = 0)
    {
        // fech post json data
        $content = $this->request->getContent();
        $post = json_decode($content, true);

        if (!$userId) throw new HttpException(422, $this->translator->trans('User id invalid.'));

        if ($accountId) {
            $post['updateByThisAccountId'] = $accountId;
        }

        $post['updateByThisUserId'] = $userId;
        $result = $this->get('CorpoEmployeesServices')->updateEmployee($post);

        if (!$result) {
            $result = array();
            $result['code'] = 423;
            $result['message'] = $this->translator->trans('Update Employee Error.');
        } else {
            // return the updated employee data.
            return $this->getProfileSettingsAction($userId, $accountId);
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);
        return $response;
    }

    /**
     * Update profile settings avatar picture
     *
     * @param integer $userId userId
     */
    public function updateProfileSettingsAvatarAction($userId)
    {
        $result = array();

        $userInfo = $this->get('UserServices')->getUserInfoById($userId);
        if (empty($userInfo)) {
            throw new HttpException(422, $this->translator->trans("User id invalid."));
        }

        $uploadedFile = $this->get('request')->files->get('image');
        if ($uploadedFile) {
            $parameters = array();
            $userParams = array();

            $upload = $this->get('CorpoUsersServices')->uploadFile($uploadedFile, $userId);

            $parameters['p_profile_picture'] = $upload['p_profile_picture'];
            $parameters['p_image_path'] = $upload['p_image_path'];

            $userParams['profile'] = $parameters['p_profile_picture'];
            $userParams['id'] = $userId;

            $update = $this->get('CorpoUsersServices')->updateCmsUsersPicture($userParams);
            $result['success'] = true;
            $result['message'] = $this->translator->trans('User avatar updated successfully!');
        } else {
            throw new HttpException(422, "Image not found");
        }

        $response = new Response(json_encode($result));
        $response->setStatusCode(200);

        return $response;
    }

    public function accountRegisterAction()
    {
        // specify required fields
        $requirements = array(
            'email',
            'password',
            'confirmPassword'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $email = ( isset($criteria['email']) )? $criteria['email'] : '';
        $username = ( isset($criteria['username']) && $criteria['username'] )? $criteria['username'] : $email;
        $password = ( isset($criteria['password']) )? $criteria['password'] : '';
        $confirmPassword = ( isset($criteria['confirmPassword']) )? $criteria['confirmPassword'] : '';

        $options = array
        (
            'yourEmail' => $email,
            'userId' => 0,
            'yourUserName' => $username,
            'fullName' => $username,
            'fname' => $username,
            'defaultPublished' => 0,
            'lang' => $this->LanguageGet(),
            'yourPassword' => $password,
            'password' => $confirmPassword
        );

        $resp = $this->get('ApiUserServices')->accountRegister($options);

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function accountInfoAction()
    {
        $user_id = $this->userGetID();
        $resp = $this->get('ApiUserServices')->getUserInfoById(intval($user_id), $this->LanguageGet());

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function updateAccountInfoAction()
    {
        // specify required fields
        $requirements = array(
            'email'
        );

        // fetch post json data
        $criteria = $this->fetchRequestData($requirements);

        $user_id = $this->userGetID();

        $gender = ( isset( $criteria['gender'] ) && $criteria['gender'] ) ? $criteria['gender'] : 'O';
        $fname = ( isset( $criteria['firstName'] ) && $criteria['firstName']) ? $criteria['firstName'] : '';
        $lname = ( isset( $criteria['lastName'] ) && $criteria['lastName']) ? $criteria['lastName'] : '';
        $birthday = ( isset( $criteria['birth'] ) && $criteria['birth']) ? $criteria['birth'] : '';
        $email = ( isset( $criteria['email'] ) && $criteria['email']) ? $criteria['email'] : '';
        $employment = ( isset( $criteria['employment'] ) && $criteria['employment']) ? $criteria['employment'] : '';
        $high_education = ( isset( $criteria['education'] ) && $criteria['education']) ? $criteria['education'] : '';
        $intrested_in = ( isset( $criteria['interestedIn'] ) && $criteria['interestedIn']) ? intval($criteria['interestedIn']) : 0;
        $hometown = ( isset( $criteria['hometown'] ) && $criteria['hometown']) ? $criteria['hometown'] : '';
        $country = ( isset( $criteria['countryCode'] ) && $criteria['countryCode']) ? $criteria['countryCode'] : '';
        $city_id = ( isset( $criteria['cityId'] ) && $criteria['cityId']) ? intval($criteria['cityId']) : 0;
        $city = ( isset( $criteria['city'] ) && $criteria['city']) ? $criteria['city'] : '';
        $website = ( isset( $criteria['website'] ) && $criteria['website']) ? $criteria['website'] : '';
        $website = str_replace('http://', '', $website);
        $website = str_replace('https://', '', $website);
        $description = ( isset( $criteria['description'] ) && $criteria['description']) ? $criteria['description'] : '';

        $save['id'] = $user_id;
        $save['gender'] = $gender;
        $save['fname'] = $fname;
        $save['lname'] = $lname;
        if ($birthday != '') {
            $save['yourBday'] = date('Y-m-d', strtotime($birthday));
        } else {
            $save['yourBday'] = NULL;
        }
        $save['yourEmail'] = $email;
        $save['employment'] = $employment;
        $save['high_education'] = $high_education;
        $save['intrested_in'] = $intrested_in;
        $save['hometown'] = $hometown;
        $save['yourCountry'] = $country;
        $save['city'] = $city;
        $save['cityId'] = intval($city_id);
        $save['websiteUrl'] = $website;
        $save['smallDescription'] = $description;

        $resp = $this->get('ApiUserServices')->updateAccountInfo($save);

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function uploadImageSaveAction()
    {
        $request = Request::createFromGlobals();
        $criteria = array_merge($request->query->all(),$request->request->all());

        $which_uploader = ( isset( $criteria['which_uploader'] ) && $criteria['which_uploader'] ) ? $criteria['which_uploader'] : 'account_pic';
        $channel_id = ( isset( $criteria['channel_id'] ) && $criteria['channel_id'] ) ? intval($criteria['channel_id']) : 0;

        $user_id = $this->userGetID();

        $options = array
        (
            'user_id' => $user_id,
            'channel_id' => $channel_id,
            'which_uploader' => $which_uploader,
            'lang' => $this->LanguageGet(),
            'files' => $_FILES
        );

        $resp = $this->get('ApiUploadsServices')->uploadImageSaveQuery( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
}
