<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Abraham\TwitterOAuth\TwitterOAuth as TwitterOAuth;

class UsersController extends DefaultController
{

    public function accountInfoAction() {

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }
        $userInfo = $this->get('UserServices')->getUserInfoById($this->data['USERID']);
        $this->data['uname'] = $userInfo['cu_yourusername'];
        $this->data['website'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_websiteUrl']);
        $this->data['desc'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_smallDescription']);
        $this->data['fname'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_fname']);
        $this->data['lname'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_lname']);
        $this->data['email'] = $userInfo['cu_youremail'];
        $this->data['employment'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_employment']);
        $this->data['education'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_highEducation']);
        $this->data['intrested_in'] = $userInfo['cu_intrestedIn'];
        
        $this->data['profile_pic'] = $userInfo['cu_profilePic'];
        if($this->data['profile_pic']!='')
        {
            $dimage = $this->data['profile_pic'];
            $dimagepath = 'media/tubers/';
            $this->data['profile_pic'] = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 230, 230, 'thumb230230', $dimagepath);
        }
        
        $dob = $userInfo['cu_yourbday'];
        if ($dob == '0000-00-00' || is_null($dob)) {
            $dob = '';
        }
        if($dob != ''){
            $dob = $dob->format('Y-m-d');
        }
        $this->data['dob'] = $dob;

        $this->data['hometown'] = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_hometown']);
        $this->data['country'] = $userInfo['cu_yourcountry'];
        $this->data['gender'] = $userInfo['cu_gender'];
        $this->data['city'] = $userInfo['w_name'];
        $this->data['city_id'] = intval($userInfo['cu_cityId']);
        $this->data['interestedInList'] = $this->get('UserServices')->getInterestedInList( $this->data['LanguageGet'] )['data'];

        $countries = array();
        $countryList = $this->getDoctrine()->getRepository('TTBundle:CmsCountries')->findBy(array(), array('name' => 'asc'));
        foreach ($countryList as $country) {
            $Selected = 0;
            if ($this->data['country'] == $country->getCode()) {
                $Selected = 1;
            }
            $countries[] = array('code' => $country->getCode(), 'name' => $country->getName(), 'selected' => $Selected);
        }
        $this->data['countries'] = $countries;
        $this->data['user_is_owner'] = 1;

        return $this->render('account/account-info.twig', $this->data);
    }

    public function accountSettingsAction() {

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }

        $userInfo = $this->get('UserServices')->getUserInfoById($this->data['USERID']);
        $this->data['uname'] = $userInfo['cu_yourusername'];

        $this->data['notification_email'] = 1;
        $notifications = $this->get('UserServices')->getNotificationsEmails($userInfo['cu_youremail']);
        if($notifications){
            $this->data['notification_email'] = $notifications['n_notify'];
        }
        $this->data['user_is_owner'] = 1;

        return $this->render('account/account-settings.twig', $this->data);
    }

    public function accountProfileAction( $username='', $params = 'photos', $seotitle, $seodescription, $seokeywords )
    {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }
        $user_is_owner = 0;
        $user_profile_id = $user_id = $this->data['USERID'];
        
        if( $username != '' ) {
            $user_profile_info = $this->get('userServices')->getUserByEmailYourUserName( trim( $username ) );

            if ( !$user_profile_info ) {
                return $this->pageNotFoundAction();
            }
            $user_profile_id = $user_profile_info['cc_id'];
        }

        if( $user_profile_id == $user_id ){
            $user_is_owner = 1;
        }
        
        if( $params == '' ) {
            $params = 'photos';
        }

        $limit = 24;
        $media_array = array();
        switch( $params )
        {
            case "photos":
                $page_title = $this->translator->trans('Profile Photos');
                $upload_title = $this->translator->trans('Upload photos');
                $srch_options = array(
                    'limit' => $limit,
                    'page' => 0,
                    'is_public' => 2,
                    'user_id' => $user_profile_id,
                    'order' => 'd',
                    'type' => 'i',
                    'lang' => $this->data['LanguageGet']
                );
                $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);

                $srch_options = array(
                    'is_public' => 2,
                    'user_id' => $user_profile_id,
                    'type' => 'i',
                    'n_results' => true
                );
                $media_count = $this->get('PhotosVideosServices')->mediaSearch($srch_options);
                break;
            case "videos":
                $page_title = $this->translator->trans('Profile Videos');
                $upload_title = $this->translator->trans('Upload videos');
                $srch_options = array(
                    'limit' => $limit,
                    'page' => 0,
                    'is_public' => 2,
                    'user_id' => $user_profile_id,
                    'order' => 'd',
                    'type' => 'v',
                    'lang' => $this->data['LanguageGet']
                );
                $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);

                $srch_options = array(
                    'is_public' => 2,
                    'user_id' => $user_profile_id,
                    'type' => 'v',
                    'n_results' => true
                );
                $media_count = $this->get('PhotosVideosServices')->mediaSearch($srch_options);
            break;
            case "albums":
                $page_title = $this->translator->trans('Profile Albums');
                $upload_title = $this->translator->trans('Upload albums');

                $srch_options = array(
                    'user_id' => $user_profile_id,
                    'n_results' => true
                );
                $media_count = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

                $srch_options = array(
                    'limit' => $limit,
                    'page' => 0,
                    'user_id' => $user_profile_id,
                    'order' => 'd'
                );
                $medialist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

                foreach ($medialist as $v_item) {
                    $varr = array();
                    $varr['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($v_item, 'small');
                    $varr['id'] = $v_item['a_id'];
                    $varr['type'] = $params;
                    $varr['link'] = $this->get("TTMediaUtils")->ReturnAlbumUriFromArray($v_item, $this->data['LanguageGet']);
                    $varr['title'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
                    $varr['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($v_item['a_catalogName']);
                    $varr['city'] = $v_item['w_name'];
                    $varr['country'] = $v_item['c_name'];
                    $location = '';
                    if( $varr['city'] != '' ) {
                        $location .= $varr['city'];
                    }
                    if( $location != '' && $varr['country'] !='' ) {
                        $location .= ', '.$varr['country'];
                    }
                    $varr['location'] = $location;
                    $media_array[] = $varr;
                }
                break;
            default:
                return $this->pageNotFoundAction();
        }
        $this->data['type'] = $params;
        $this->data['media_count'] = $media_count;
        $this->data['page_count'] = ceil($media_count / $limit) - 1;
        $this->data['media_array'] = $media_array;
        $this->data['page_title'] = $page_title;
        $this->data['upload_title'] = $upload_title;
        $this->data['account_header_name'] = strtolower($page_title);
        $this->data['user_is_owner'] = $user_is_owner;
        $this->data['username'] = $username;

        return $this->render('account/my-posts.twig', $this->data);
    }

    public function getProfileMediaDataAction()
    {
        $request    = Request::createFromGlobals();
        $user_profile_id = $user_id    = $this->userGetID();
        $type = $request->request->get('media_type', '');
        $username = $request->request->get('media_usertype', '');
        $page       = intval($request->request->get('page', 0));

        $limit         = 24; 
        $user_is_owner = 0;

        if( $username != '' ) {
            $user_profile_info = $this->get('userServices')->getUserByEmailYourUserName( trim( $username ) );

            if ( !$user_profile_info ) {
                $all_info['data']  = '';
                $res = new Response(json_encode($all_info));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
            $user_profile_id = $user_profile_info['cc_id'];
        }

        if( $user_profile_id == $user_id ){
            $user_is_owner = 1;
        }

        $media_array = array();
        switch( $type )
        {
            case "":
            case "photos":
                $srch_options = array(
                    'limit' => $limit,
                    'page' => $page,
                    'is_public' => 2,
                    'user_id' => $user_profile_id,
                    'order' => 'd',
                    'type' => 'i',
                    'lang' => $this->data['LanguageGet']
                );
                $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);
                break;
            case "videos":
                $srch_options = array(
                    'limit' => $limit,
                    'page' => $page,
                    'is_public' => 2,
                    'user_id' => $user_profile_id,
                    'order' => 'd',
                    'type' => 'v',
                    'lang' => $this->data['LanguageGet']
                );
                $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);
            break;
            case "albums":
                $srch_options = array(
                    'limit' => $limit,
                    'page' => $page,
                    'user_id' => $user_profile_id,
                    'order' => 'd'
                );
                $medialist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

                foreach ($medialist as $v_item) {
                    $varr = array();
                    $varr['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($v_item, 'small');
                    $varr['id'] = $v_item['a_id'];
                    $varr['type'] = $params;
                    $varr['link'] = $this->get("TTMediaUtils")->ReturnAlbumUriFromArray($v_item, $this->data['LanguageGet']);
                    $varr['title'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
                    $varr['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($v_item['a_catalogName']);
                    $varr['city'] = $v_item['w_name'];
                    $varr['country'] = $v_item['c_name'];
                    $location = '';
                    if( $varr['city'] != '' ) {
                        $location .= $varr['city'];
                    }
                    if( $location != '' && $varr['country'] !='' ) {
                        $location .= ', '.$varr['country'];
                    }
                    $varr['location'] = $location;
                    $media_array[] = $varr;
                }
                break;
            default:
                $all_info['data']  = '';
                $res = new Response(json_encode($all_info));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
        }

        $data_list = array();
        $data_list['user_is_owner'] = $user_is_owner;
        $data_list['media_array'] = $media_array;
        
        $all_info['data']  = $this->render('account/my-posts_in.twig', $data_list)->getContent();

        $res = new Response(json_encode($all_info));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function accountUploadAction( $seotitle, $seodescription, $seokeywords )
    {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_welcome', array(), 301);
        }

        $this->setHreflangLinks( $this->generateLangRoute('_account_upload'), true, true);

        $this->data['datapagename'] = 'uploads_page';
        $this->data['upload_max_filesize'] = intval(ini_get('upload_max_filesize'));
        $this->data['channel_id'] = 0;
        
        if ($this->data['aliasseo'] == '') {            
            $action_text_display = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seodescription, array(), 'seo');
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $this->data['section_name'] = $this->translator->trans('User');

        $srch_options = array(
            'limit' => null,
            'is_owner' => 1,
            'show_empty' => 1,
            'orderby' => 'catalogName',
            'order' => 'a',
            'user_id' => $this->data['USERID']
        );
        $albumlist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

        $album_array = array();
        foreach ($albumlist as $v_item) {
            $varr = array();
            $varr['id'] = $v_item['a_id'];
            $varr['name'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
            $album_array[] = $varr;
        }
        $this->data['album_array'] = $album_array;

        return $this->render('uploads/uploads.twig', $this->data);
    }

    public function accountSharingAction( Request $request, $params='', $page_type='' )
    {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }
        $user_id = $this->data['USERID'];
        $code = $request->query->get('code', '');
        $oauth_token = $request->query->get('oauth_token', '');
        $oauth_verifier = $request->query->get('oauth_verifier', '');
        $state = $request->query->get('state', '');
        $msg = '';

        $isAuthorizedTwitter = $this->get('UserServices')->userAlreadyAuthorized( $user_id, 'twitter', 1 );
        $isAuthorizedfb = $this->get('UserServices')->userAlreadyAuthorized( $user_id, 'fb', 1 );
        
        $params_type = $page_type.'-'.$params;
        switch( $params_type )
        {
            case 'redirect-twitter':
                if (sizeof($isAuthorizedTwitter) > 0)
                {
                    return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                }

                $isActiveConnection = $this->get('UserServices')->userAlreadyAuthorized( $user_id, 'twitter', 0 );

                if ( !empty($isActiveConnection) && isset($isActiveConnection[0]) )
                {
                    $this->get('UserServices')->updateUserSocialCredential( $user_id, 'twitter', 1 );
                    return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                }
                define('CONSUMER_KEY', 'myByt10HlKYWFv9B0xMvkTj3Y');
                define('CONSUMER_SECRET', 'YfPrUjORSLdG93fwIeiPGplhQQpZrGIhG2uJQbfkSo2y4IhI12');
                define('OAUTH_CALLBACK', 'https://www.touristtube.com/account/callback-twitter');

                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
                
                $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

                $expire = time() + 365 * 24 * 3600;
                $pathcookie = '/';
                $token = $request_token['oauth_token'];
                setcookie("oauth_token", $token , $expire, $pathcookie, $CONFIG['cookie_path']);
                setcookie("oauth_token_secret", $request_token['oauth_token_secret'] , $expire, $pathcookie, $this->container->getParameter('CONFIG_COOKIE_PATH'));

                switch ($connection->http_code) {
                    case 200:
                        /* Build authorize URL and redirect user to Twitter. */
                        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
                        header('Location: ' . $url);
                        break;
                    default:
                        /* Show notification if something went wrong. */
                        $msg = $this->translator->trans('Could not connect to Twitter. Refresh the page or try again later.');
                }
            break;

            case 'callback-twitter':
                if (sizeof($isAuthorizedTwitter) > 0)
                {
                    return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                }
                $oauth_token = $request->cookies->get('oauth_token', false);
                $oauth_token_secret = $request->cookies->get('oauth_token_secret', false);
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);

                /* Request access tokens from twitter */
                $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

                switch ($connection->http_code) {
                    case 200:
                        if ( !$this->get('UserServices')->setUserSocialCredential( $user_id, $access_token, 'twitter' ) )
                        {//die('503 error');
                            return $this->redirect($this->generateUrl( '_welcome', array() ) );
                        }
                        return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                    break;
                }
                $msg = $this->translator->trans('Could not connect to Twitter. Refresh the page or try again later.');
            break;

            case 'redirect-fb':
                if (sizeof($isAuthorizedfb) > 0)
                {
                    return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                }
                
                $isActiveConnection = $this->get('UserServices')->userAlreadyAuthorized( $user_id, 'fb', 0 );

                if ( !empty($isActiveConnection) && isset($isActiveConnection[0]) )
                {
                    $this->get('UserServices')->updateUserSocialCredential( $user_id, 'fb', 1 );
                    return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                } 

                return $this->redirect($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/account/callback-fb') );
            break;

            case 'callback-fb':
                $uricurserver = $this->get('TTRouteUtils')->currentServerURL();
                define('FB_CALLBACKURL', $uricurserver . '/account/callback-fb');
                require_once($this->container->getParameter('CONFIG_SERVER_ROOT') . 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php');

                $facebook = new \Facebook\Facebook([
                    'appId' => '1045138925510219',
                    'app_id' => '1045138925510219',
                    'secret' => '87378d17c481361e8f8d526da84b3e50',
                    'app_secret' => '87378d17c481361e8f8d526da84b3e50'
                ]);

                $helper = $facebook->getRedirectLoginHelper();

                if ( $code=='' || $state=='' )
                {
                    $fbUrl = $helper->getLoginUrl(FB_CALLBACKURL,array('scope' => 'publish_actions'));
                    header('Location: ' . $fbUrl);
                } else {
                    try {
                        $fb_token = $helper->getAccessToken(FB_CALLBACKURL);
                        try {
                            // Returns a `Facebook\FacebookResponse` object
                            $response = $facebook->get('/me?fields=id,name', $fb_token->getValue());
                        } catch(Facebook\Exceptions\FacebookResponseException $e) {
                            echo 'Graph returned an error: ' . $e->getMessage();
                            exit;
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            echo 'Facebook SDK returned an error: ' . $e->getMessage();
                            exit;
                        }
                        $fbUser = $response->getGraphUser();
                    } catch(Facebook\Exceptions\FacebookResponseException $e) {
                        // When Graph returns an error
                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                    } catch(Facebook\Exceptions\FacebookSDKException $e) {
                        // When validation fails or other local issues
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }
                    $userFBInf['user_id'] = $fbUser->getId();
                    $userFBInf['oauth_token'] = $fb_token->getValue();
                    $userFBInf['oauth_token_secret'] = $code;
                    if ( !$this->get('UserServices')->setUserSocialCredential( $user_id, $userFBInf, 'fb' ) )
                    {//die('503 error');
                        return $this->redirect($this->generateUrl( '_welcome', array() ) );
                    }
                    return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
                }
            break;

            case 'disconnect-twitter':
            case 'disconnect-fb':
                $this->get('UserServices')->updateUserSocialCredential( $user_id, $params, 0 );
                return $this->redirect($this->generateUrl( '_account_sharing', array() ) );
            break;
        }

        if (sizeof($isAuthorizedfb) < 1) {
            $this->data['fbLink'] = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/account/redirect-fb');
            $this->data['fbConnect'] = $this->translator->trans('connect');
        } else {
            $this->data['fbLink'] = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/account/disconnect-fb');
            $this->data['fbConnect'] = $this->translator->trans('disconnect');
        }

        if (sizeof($isAuthorizedTwitter) < 1) {
            $this->data['twitterLink'] = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/account/redirect-twitter');
            $this->data['twitterConnect'] = $this->translator->trans('connect');
        } else {
            $this->data['twitterLink'] = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/account/disconnect-twitter');
            $this->data['twitterConnect'] = $this->translator->trans('disconnect');
        }
        $this->data['msg'] = $msg;
        $this->data['user_is_owner'] = 1;

        return $this->render('account/account-sharing.twig', $this->data);
    }

    public function updateAccountSettingsAction()
    {
        $request    = Request::createFromGlobals();
        $user_id = $this->data['USERID'];
        $Result = array();

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $uname = $request->request->get('uname', '');
        $old_pass = $request->request->get('old_pass', '');
        $new_pass = $request->request->get('new_pass', '');
        $stopemail = intval($request->request->get('stopemail', 1));

        if (!$this->get('UserServices')->userPasswordCorrect($user_id, $old_pass))
        {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Password is incorrect');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $userInfo = $this->get('UserServices')->getUserInfoById($user_id);
        $email = $userInfo['cu_youremail'];
        if(!$this->get('UserServices')->addNotificationsEmails($email,$stopemail))
        {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Couldn\'t save information. Please try again later.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $this->get('UserServices')->checkDuplicateUserName( $user_id, $uname ) )
        {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Username is not unique. Please specify another.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if( !$this->get('app.utils')->validate_alphanumeric_underscore($uname) ){
            $Result['msg'] = $this->translator->trans('Invalid user name.');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $save['id'] = $user_id;
        $save['yourUserName'] = $uname;
        $success = $this->get('UserServices')->modifyUser($save);
        if (is_array($success) && isset($success['error']) && !empty($success['error']))
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save username. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( strlen($new_pass) >0 ) {
            if (strlen($new_pass) < 8)
            {
                $Result['status'] = 'error';
                $Result['msg'] = $this->translator->trans('Your password is too short.');
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $new_pass))
            {
                $Result['status'] = 'error';
                $Result['msg'] = $this->translator->trans('Your password is not secure.');
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            $success = $this->get('UserServices')->updateUserPassword( $user_id, $new_pass );
            if (is_array($success) && isset($success['success']) && !$success['success'] )
            {
                $Result['status'] = 'error';
                $Result['msg'] = $this->translator->trans("Couldn't save password. Please try again later.");
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        $Result['msg'] = $this->translator->trans('Account information saved!');
        $Result['status'] = 'ok';

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }
    
    public function userReportAddAction()
    {
        $request    = Request::createFromGlobals();
        $user_id = $this->data['USERID'];

        $reason = $request->request->get('reason', '');
        $msg = $request->request->get('msg', '');
        $entity_type = intval($request->request->get('entity_type', 0));
        $channel_id = intval($request->request->get('channel_id', 0));
        $entity_id = intval($request->request->get('entity_id', 0));
	$title = $request->request->get('title', '');
	$email = $request->request->get('email', '');
        $owner_id = 0;
        
        $Result = array();

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $entity_type == $this->container->getParameter('SOCIAL_ENTITY_CHANNEL') && $channel_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ( $entity_type == $this->container->getParameter('SOCIAL_ENTITY_CHANNEL') )
        {
            $channelInfo = $this->get('ChannelServices')->channelGetInfo( $channel_id, $this->data['LanguageGet'] );

            if( !$channelInfo )
            {
                $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }

            $owner_id = $channelInfo['c_ownerId'];
        }

        $options['user_id'] = $user_id;
        $options['owner_id'] = $owner_id;
        $options['reason'] = $reason;
        $options['msg'] = $msg;
        $options['entity_type'] = $entity_type;
        $options['channel_id'] = $channel_id;
        $options['entity_id'] = $entity_id;
        $options['title'] = $title;
        $options['email'] = $email;

        if( $report_id = $this->get('UserServices')->addReportData( $options ) )
        {
            $Result['msg'] = $this->translator->trans('Your report has been successfully submitted.').'<br/>'.$this->translator->trans('We will take adequate action based on our terms of use and conditions.');
            $Result['status'] = 'ok';
            $Result['id'] = $report_id;
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        } else {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
        }
    }

    /**
     * Users confirm login
     *
     */
    public function userConfirmLoginAction()
    {
        return $this->render('account/user-confirm-login.twig', $this->data);
    }

    public function updateAccountCheckUserAction()
    {
        $request    = Request::createFromGlobals();
        $user_id = $this->data['USERID'];
        $Result = array();

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $action = $request->request->get('action', '');
        $uname = $request->request->get('uname', '');
        $old_pass = $request->request->get('old_pass', '');

        if (!$this->get('UserServices')->userPasswordCorrect($user_id, $old_pass, $uname))
        {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Incorrect username or password');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('Account information saved!');
        $Result['status'] = 'ok';
        switch($action){
            case "deactivate":
                $save['id'] = $user_id;
                $save['published'] = -1;
                $success = $this->get('UserServices')->modifyUser($save);
                if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
                    $Result['msg'] = $this->translator->trans('Couldn\'t save information. Please try again later');
                    $Result['status'] = 'error';
                }else{
                    $success = $this->get('UserServices')->userDeleteDeactivateEntities($user_id, 'deactivate');
                    $Result['msg'] = $this->translator->trans('Your account is deactivated');
                    $Result['status'] = 'ok';
                }
            break;
            case "delete":
                $save['id'] = $user_id;
                $save['published'] = -2;
                $success = $this->get('UserServices')->modifyUser($save);
                if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
                    $Result['msg'] = $this->translator->trans('Couldn\'t save information. Please try again later');
                    $Result['status'] = 'error';
                }else{
                    $success = $this->get('UserServices')->userDeleteDeactivateEntities($user_id, 'delete');
                    $Result['msg'] = $this->translator->trans('Your account is deleted');
                    $Result['status'] = 'ok';
                }
            break;
        }
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function updateAccountInfoAction()
    {
        $request    = Request::createFromGlobals();
        $user_id = $this->data['USERID'];
        $Result = array();

        if ( $user_id == 0 )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $err = '';
        $description = $request->request->get('description', '');
        $website = $request->request->get('website', '');
        $website = str_replace('http://', '', $website);
        $fname = $request->request->get('fname', '');
        $lname = $request->request->get('lname', '');
        $employment = $request->request->get('employment', '');
        $high_education = $request->request->get('high_education', '');
        $intrested_in = intval($request->request->get('intrestedin', 0));
        $email = $request->request->get('email', '');
        $birthday = $request->request->get('birthday', '');
        $gender = $request->request->get('gender', 'O');
        $hometown = $request->request->get('hometown', '');
        $city = $request->request->get('city', '');
        $city_id= intval($request->request->get('cityid', 0));
        $country = $request->request->get('country', '');

        if($email!='user@touristtube.com'){
            if (!$this->get('app.utils')->check_email_address($email) || $email == '' ) {
                $err .= $this->translator->trans('Please enter a valid email'). "<br />";
            } else if ($this->get('UserServices')->checkDuplicateUserEmail($user_id, $email)) {
                $err .= $this->translator->trans('This email address belongs to an existing Tourist Tuber;'). "<br />";
                $err .= $this->translator->trans("Please sign in using this email or reset your password");
            }
        }

        $save['id'] = $user_id;
        $save['websiteUrl'] = $website;
        $save['smallDescription'] = $description;
        $save['fname'] = $fname;
        $save['lname'] = $lname;
        $save['employment'] = $employment;
        $save['high_education'] = $high_education;
        $save['intrested_in'] = $intrested_in;
        $save['yourEmail'] = $email;
        if ($birthday != '') {
            $save['yourBday'] = date('Y-m-d', strtotime($birthday));
        } else {
            $save['yourBday'] = NULL;
        }
        $save['gender'] = $gender;
        $save['hometown'] = $hometown;
        $save['city'] = $city;
        $save['cityId'] = intval($city_id);
        $save['yourCountry'] = $country;
        if ($err != '') {
            $Result['msg'] = $err;
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }
        $success = $this->get('UserServices')->modifyUser($save);
        if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save information. Please try again later');
            $Result['status'] = 'error';
        } else{
            $Result['msg'] = $this->translator->trans('Account information saved!');
            $Result['status'] = 'ok';
        }
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    /**
     *  Action for activating user's account
     *
     * @return twig
     */
    public function activateUsersAccountAction($key = '', $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        
        $this->data['showfooter'] = 0;
        if ($key == '')
        {
            return $this->pageNotFoundAction();
        }

        $userInfo = $this->get('UserServices')->checkUserEmailMD5($key);

        if ( !$userInfo )
        {
            return $this->pageNotFoundAction();
        }

        if ($this->get('UserServices')->activateUsersAccount($userInfo['cu_id']))
        {
            $displayMessage = $this->translator->trans('Your account is activated. You may login now.');
        } else {
            $displayMessage = $this->translator->trans('your activation link has expired.');
        }
        
        $this->data['key']            = $key;
        $this->data['displayMessage'] = $displayMessage;
        
        return $this->render('@TT/Users/activate-account.twig', $this->data);
    }

    public function reactivateUsersAccountAction( $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->data['showfooter'] = 0;

        return $this->render('@TT/Users/reactivate-account.twig', $this->data);
    }

    public function reactivateNotificationsAction( Request $request )
    {
        $Result = array();
        $email = $request->request->get('email', '');

        if ( $email == '' )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $user_info = $this->get('UserServices')->checkUserEmailMD5( $email );
        
        if ( !$user_info )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('Your email notifications has been re-activated');
        $Result['status'] = 'ok';
        if( !$this->get('UserServices')->addNotificationsEmails( $user_info['cu_youremail'], 1 ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function userTTConfirmationAction( Request $request, $params, $key, $seotitle, $seodescription, $seokeywords )
    {
        $expire           = time() + 300;
        $pathcookie       = '/';
        $configCookiePath = $this->container->getParameter('CONFIG_COOKIE_PATH');
        setcookie("referer_page", $request->getUri(), $expire, $pathcookie, $configCookiePath);

        $this->data['msg'] = '';
        $this->data['error'] = '';
        $user_is_owner=0;
        if( $this->data['isUserLoggedIn'] == 1 )
        {
            $user_id = $this->data['USERID'];
            switch( $params )
            {
                case "channel":
                    $channelInfo = $this->get('ChannelServices')->checkChannelOwner( $key , $user_id );
                    if( !$channelInfo )
                    {
                        $this->userLogout();
                        return $this->redirect($request->getUri());
                    }
                    else
                    {
                        $user_is_owner=1;
                        $create_ts = $channelInfo['c_createTs'];
                        $date_now  = $create_ts->format('Y-m-d');
                        $date_now_before = date('Y-m-d', time() - 604800);
                        $deactivatedTs =  date('Y-m-d', $create_ts->getTimestamp() - 86400);

                        $update_list['id'] = $channelInfo['c_id'];
                        $update_list['ownerId'] = $user_id;
                        $update_list['deactivatedTs'] = $deactivatedTs;
                        if( $date_now>=$date_now_before )
                        {
                            $update_list['published'] = 1;
                            $this->get('ChannelServices')->channelEdit( $update_list );
                            header('Location:' . $this->generateLangRoute( '_create_channel', array('id' => $channelInfo['c_id'] ) ) );
                        }
                        else
                        {
                            $update_list['published'] = -2;
                            $this->get('ChannelServices')->channelEdit( $update_list );
                            $this->data['msg'] = $this->translator->trans('your activation link has expired');
                        }
                    }
                    break;
                case "channelHome":
                    $channelInfo = $this->get('ChannelServices')->checkChannelOwner( $key , $user_id );
                    if( !$channelInfo )
                    {
                        $this->userLogout();
                        return $this->redirect($request->getUri());
                    }
                    else
                    {
                        $user_is_owner=1;
                        $update_list['id'] = $channelInfo['c_id'];
                        $update_list['ownerId'] = $user_id;
                        $update_list['published'] = 1;
                        $this->get('ChannelServices')->channelEdit( $update_list );
                        header('Location:' . $this->generateLangRoute( '_channel', array('srch' => $channelInfo['c_channelUrl'] ) ) );
                    }
                break;
                case "channelSettings":
                    $channelInfo = $this->get('ChannelServices')->checkChannelOwner( $key , $user_id );
                    if( !$channelInfo )
                    {
                        $this->userLogout();
                        return $this->redirect($request->getUri());
                    }
                    else
                    {
                        $user_is_owner=1;
                        header('Location:' . $this->generateLangRoute( '_channel_settings', array('id' => $channelInfo['c_id'] ) ) );
                    }
                    break;
                default:
                    return $this->pageNotFoundAction();
            }
        }

        $this->data['user_is_owner'] = $user_is_owner;
        
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->data['showfooter'] = 0;

        return $this->render('@TT/Users/tt-confirmation.twig', $this->data);
    }

    public function unsubscribeEmailsAction( $key, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['msg'] = '';
        $this->data['email'] = '';
        $this->data['email_md5'] = '';
        
        $user_info = $this->get('UserServices')->checkUserEmailMD5($key);
        if ( !$user_info ) $this->data['msg'] = $this->translator->trans('Invalid user credentials. Please try again.');

        if( $this->data['msg'] == '' )
        {
            $this->data['email'] = $user_info['cu_youremail'];
            $this->data['email_key'] = $key;

            if( !$this->get('UserServices')->addNotificationsEmails( $this->data['email'], 0 ) )
            {
                $this->data['msg'] = $this->translator->trans('Couldn\'t save information. Please try again later');
            }
        }
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->data['showfooter'] = 0;

        return $this->render('@TT/Users/unsubscribe-emails.twig', $this->data);
    }

    public function userUnsubscribeCodeAction( $key, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['msg'] = '';
        $this->data['email'] = '';
        $this->data['email_md5'] = '';

        $user_info = $this->get('UserServices')->checkUserEmailMD5($key);
        if ( !$user_info ) $this->data['msg'] = $this->translator->trans('Invalid user credentials. Please try again.');

        if( $this->data['msg'] == '' )
        {
            $this->data['email'] = $user_info['cu_youremail'];
            $this->data['email_key'] = $key;
        }
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->data['showfooter'] = 0;

        return $this->render('@TT/Users/user-unsubscribe-code.twig', $this->data);
    }

    public function processFBAction( Request $request )
    {
        $Result = array();

        $access_token = $request->request->get('access_token', '');
        $longitude = floatval($request->request->get('longitude', 0));
        $latitude = floatval($request->request->get('latitude', 0));

        $config_use = array(
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'appId' => $this->container->getParameter('facebook_app_id'),
            'app_secret' => $this->container->getParameter('facebook_app_secret'),
            'secret' => $this->container->getParameter('facebook_app_secret'),
            'default_graph_version' => $this->container->getParameter('facebook_default_graph_version')
        );

        try
        {
            $fb  = new \Facebook\Facebook($config_use);
            $response = $fb->get('/me?fields=id,name,email,birthday,first_name,last_name', $access_token);
        } 
        catch(Facebook\Exceptions\FacebookResponseException $e)
        {
            // When Graph returns an error
            $Result['status'] = 'error';
            $Result['msg'] = 'Graph returned an error: ' . $e->getMessage();
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        } 
        catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            $Result['status'] = 'error';
            $Result['msg'] = 'Facebook SDK returned an error: ' . $e->getMessage();
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $user_profile = $response->getGraphUser();
        if( !isset($user_profile['email']) || is_null($user_profile['email']) || $user_profile['email']=='')
        {
            $Result['status'] = 'error';
            $Result['msg'] = 'Facebook SDK returned an error: Invalid Facebook account';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $user_email = $user_profile['email'];
        $check_email  = $this->get('UserServices')->getUserDetails( array( 'yourEmail'=>$user_email ) );
        
        if( isset( $user_profile['birthday'] ) ){
            $birthday= $user_profile['birthday'];
            $birthday_str = $birthday->format('Y-m-d');
        }
        else{
            $birthday_str = null;
        }

        if( $check_email && isset($check_email[0]) )
        {
            $res = $this->get('UserServices')->userUpdateFbUser( $check_email[0]['cu_id'], $user_profile['id'], $user_profile['id'] );
        }
        else
        {
            $post['fullName'] = $user_profile['name'];
            $post['yourEmail'] = $user_profile['email'];
            $post['fname'] = $user_profile['first_name'];
            $post['lname'] = $user_profile['last_name'];
            $post['yourBday'] = $birthday_str;
            $post['fb_token'] = $access_token;
            $post['fb_user'] = $user_profile['id'];
            $post['password'] = $user_profile['id'];
            $post['gender'] = 'O';
            $post['yourUserName'] = $user_profile['email'];
            $post['defaultPublished'] = 1;
            $post['cmsUserGroupId']   = $this->container->getParameter('ROLE_USER');

            $res = $this->get('UserServices')->generateUser($post);
        }

        $Result['status'] = 'ok';
        $Result['uname'] = $user_profile['email'];
        $Result['fb_user'] = $user_profile['id'];
        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function userUnsubscribeDeleteAccountAction( Request $request )
    {
        $Result = array();
        $code = $request->request->get('code', '');

        if ( $code == '' )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $Result['msg'] = $this->translator->trans('The tuber account created using your email address, has been deleted on Tourist Tube as per your request.');
        $Result['status'] = 'ok';
        if( !$this->get('UserServices')->deleteUserEmailMD5( $code ) )
        {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    /**
     *  Action for saving user account registration through AJAX call
     *
     * @return JSON Response
     */
    public function addUserSubmitAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $result   = $error    = $messages = array();
        $success  = true;

        if ( !isset($post['yourEmail']) || $post['yourEmail'] == '' )
        {
            $messages[] = $this->translator->trans('Please enter a valid email');
            $success    = false;
        }
        else if (isset($post['yourEmail']) && $post['yourEmail'])
        {
            if ($this->get('UserServices')->checkDuplicateUserEmail($post['userId'], $post['yourEmail'])) {
                $messages[] = $this->translator->trans('This email address belongs to an existing Tourist Tuber.');
                $success    = false;
            }
        }
        if (isset($post['yourUserName']) && $post['yourUserName']) {
            if ($this->get('UserServices')->checkDuplicateUserName($post['userId'], $post['yourUserName'])) {
                $suggestedNewUserNames = $this->get('UserServices')->suggestUserNameNew($post['yourUserName']);
                $messages[]            = $this->translator->trans('Your username is already taken. try:').' '.implode(' or ', $suggestedNewUserNames);
                $success               = false;
            }
        }

        if ( !$post['yourPassword'] || !$post['password'] || $post['yourPassword'] != $post['password'] || $post['yourPassword'] == '' )
        {
            $messages[]            = $this->translator->trans('Invalid password.');
            $success               = false;
        }
        else if (strlen($post['yourPassword']) < 8)
        {
            $messages[]            = $this->translator->trans('Your password is too short.');
            $success               = false;
        }

        if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $post['yourPassword']))
        {
            $messages[]            = $this->translator->trans('Your password is not secure.');
            $success               = false;
        }

        
        if ( $post['yourUserName'] == '' ) {
            $post['yourUserName'] = $post['yourEmail'];
        }

        if ($success) {
            $post['fullName']         = $post['fname']            = $post['yourUserName'];
            $post['defaultPublished'] = 0;
            $post['cmsUserGroupId']   = $this->container->getParameter('ROLE_USER');

            $insert = $this->get('UserServices')->generateUser($post);

            if (is_array($insert) && isset($insert['error']) && !empty($insert['error'])) {
                $messages[] = $insert['error'];
                $success    = false;
            } else {
                $this->get('UserServices')->sendRegisterActivationEmail(array('userId' => $insert->getId(), 'activationLink' => '/users/activate-account/', 'langCode' => $this->LanguageGet()));
                $messages[] = 'An email is sent to you to activate your account.';
            }
        }

        $result['success'] = $success;
        $result['message'] = (!empty($messages) && $messages) ? implode(' ', $messages) : $messages;

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}