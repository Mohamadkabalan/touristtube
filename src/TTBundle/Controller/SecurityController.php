<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends DefaultController
{
    private $secretKey = "fakesecretkey";
    private $CLIENT_TYPE = 0;
    public function loginAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $referer  = $request->headers->get('referer');
        $SERVER_NAME = $request->server->get('HTTP_HOST', '');
        $baseUrl = $SERVER_NAME.$request->getBaseUrl();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        if( $lastPath != '' && $lastPath != '/' && $lastPath != '/login' && $lastPath != '/login_success' && $lastPath != '/logout' )
        {
            $expire           = time() + 300;
            $pathcookie       = '/';
            $configCookiePath = $this->container->getParameter('CONFIG_COOKIE_PATH');
            setcookie("referer_page", $referer, $expire, $pathcookie, $configCookiePath);
        }

        //if web user already logged in then redirect to home page
        if ($this->data['isUserLoggedIn'] == 1) {
         return $this->redirectToLangRoute('_welcome');
        }


        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername                = $authenticationUtils->getLastUsername();
        $this->data['last_username'] = $lastUsername;
        $this->data['error']         = $error;
//        $this->setHreflangLinks("/login");
        $this->data['facebook_app_id']                = $this->container->getParameter('facebook_app_id');
        $this->data['facebook_default_graph_version'] = $this->container->getParameter('facebook_default_graph_version');
        return $this->render(
                '@TT/Security/login.html.twig', $this->data
        );
    }

    /*
     * Success handler after validating username and password
     * User Functionality:
     * published: -2 deleted users
     * published: 0 newly created users
     * - Redirect back to login with error Inactive user
     * published: -1 reactivate user and login normally
     * published 1: log in normally
     */

    public function loginSuccessAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $user      = $this->getUser();
        $userId    = $user->getId();
        $published = $user->getPublished();        
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }

        if (!$published || $published == -2 || $published == 0) {
            return $this->redirectToLangRoute('_log_in', array('validation_error' => 1));
        }
        
        //then if success
        $request   = $this->get('request');
        $longitude = floatval($request->request->get('longitude', 0));
        $latitude  = floatval($request->request->get('latitude', 0));

        //set keep me logg in same as remember me os symfony which preserves the cookie "REMEMBERME" built by default in symfony security
        $keepMeLogged = 0;

        $remember_cookie = $request->cookies->get('keepme_logged', '');
        if (isset($remember_cookie) && !empty($remember_cookie)) {
            $keepMeLogged = 1;
        }

        $data  = time()."_".$userId;
        $token = hash('sha512', $this->secretKey.$data);

        $params = array(
            'userId' => $userId,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'ipAddress' => $request->server->get('REMOTE_ADDR', ''),
            'forwardedIpAddress' => $request->server->get('HTTP_X_FORWARDED_FOR', ''),
            'userAgent' => $request->server->get('HTTP_USER_AGENT', ''),
            'uid' => $token,
            'clientType' => $this->CLIENT_TYPE,
            'socialToken' => '',
            'keepMeLogged' => $keepMeLogged
        );

        if ($longitude != 0 && $latitude != 0) {
            $profilePosition = $this->get('UserServices')->userSetProfilePosition($params);
        }

        if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            $userInfo = $this->get('UserServices')->userSetSession($userId);
            $this->get('ApiUserServices')->tt_global_set('userInfo', $userInfo);
        }

        //reactivating user
        if ($published == -1) {
            $this->get('UserServices')->activateUsersAccount($userId, true);
        }

        $this->get('UserServices')->userLoginTrack($params);
        $this->get('UserServices')->userToSession($params);

        $expire           = time() + 365 * 24 * 3600;
        $pathcookie       = '/';
        $configCookiePath = $this->container->getParameter('CONFIG_COOKIE_PATH');

        $currentChannel = $user->getIschannel() ? $this->get('UserServices')->userDefaultChannelGet($userId) : false;
        if ($currentChannel) {
            setcookie("current_channel", $currentChannel['cc_channelUrl'], $expire, $pathcookie, $configCookiePath);
        }

        if ($keepMeLogged == 1) {
            setcookie("lt", $token, $expire, $pathcookie, $configCookiePath);
        } else {
            $user   = $this->getUser();
            $userId = $user->getId();

            $published = $user->getPublished();

            if ($published == 1 || $published == -1) {
                if ($published == -1) {
                    $this->get('UserServices')->activateUsersAccount($userId, true);
                }

                $request      = $this->get('request');
                $longitude    = floatval($request->request->get('longitude', 0));
                $latitude     = floatval($request->request->get('latitude', 0));

                //set keep me logg in same as remember me os symfony which preserves the cookie "REMEMBERME" built by default in symfony security
                $keepMeLogged = 0;

                $remember_cookie = $request->cookies->get('keepme_logged', '');
                if (isset($remember_cookie) && !empty($remember_cookie)) {
                    $keepMeLogged = 1;
                }

                $data  = time()."_".$userId;
                $token = hash('sha512', $this->secretKey.$data);

                $params = array(
                    'userId' => $userId,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'ipAddress' => $request->server->get('REMOTE_ADDR', ''),
                    'forwardedIpAddress' => $request->server->get('HTTP_X_FORWARDED_FOR', ''),
                    'userAgent' => $request->server->get('HTTP_USER_AGENT', ''),
                    'uid' => $token,
                    'clientType' => $this->CLIENT_TYPE,
                    'socialToken' => '',
                    'keepMeLogged' => $keepMeLogged
                );

                if ($longitude != 0 && $latitude != 0) {
                    $profilePosition = $this->get('UserServices')->userSetProfilePosition($params);
                }

                if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
                    $userInfo             = $this->get('UserServices')->userSetSession($userId);
                    $this->get('ApiUserServices')->tt_global_set('userInfo', $userInfo);
                }

                $this->get('UserServices')->userLoginTrack($params);
                $this->get('UserServices')->userToSession($params);

                $expire           = time() + 365 * 24 * 3600;
                $pathcookie       = '/';
                $configCookiePath = $this->container->getParameter('CONFIG_COOKIE_PATH');

                $currentChannel = $user->getIschannel() ? $this->get('UserServices')->userDefaultChannelGet($userId) : false;
                if ($currentChannel) {
                    setcookie("current_channel", $currentChannel['cc_channelUrl'], $expire, $pathcookie, $configCookiePath);
                }

                if ($keepMeLogged == 1) {
                    setcookie("lt", $token, $expire, $pathcookie, $configCookiePath);
                } else {
                    setcookie("lt", $token, 0, $pathcookie, $configCookiePath);
                }
                return $this->checkTraitRedirection( $request );
            } else {
                setcookie("lt", $token, 0, $pathcookie, $configCookiePath);
                return $this->redirectToLangRoute("_log_in", array('validation_error' => 1));
            }
        }
        return $this->checkTraitRedirection( $request );
    }

    private function checkTraitRedirection( $request )
    {
        $pathcookie       = '/';
        $configCookiePath = $this->container->getParameter('CONFIG_COOKIE_PATH');
        $referer_page = $request->cookies->get('referer_page', '');
        setcookie("referer_page", '', 0, $pathcookie, $configCookiePath);
        if( $referer_page != '' )
        {
            return $this->redirect( $referer_page );
        }
        else
        {
            $key = '_security.user_secured_area.target_path';

            if ($this->container->get('session')->has($key))
            {
                $url = $this->container->get('session')->get($key);
                $this->container->get('session')->remove($key);
                return new RedirectResponse( $url );
            }else return $this->redirectToLangRoute('_welcome');
        }
    }

    /**
     * Forgot password page
     */
    public function forgotPasswordAction($key = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['showfooter'] = 0;
        if ($key == '') return $this->pageNotFoundAction();
        $user_info                = $this->checkUserEmailMD5($key);
        if ( !$user_info ) return $this->pageNotFoundAction();
        $this->data['key']        = $key;
        return $this->render('@TT/Security/user-forgot-password.twig', $this->data);
    }

    /**
     * Tell Us page
     */
    public function tellUsAction($key = '', $seotitle, $seodescription, $seokeywords)
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

        $userInfo = $this->checkUserEmailMD5($key);
        if ( !$userInfo )
        {
            return $this->pageNotFoundAction();
        }
        
        $this->data['key']        = $key;
        
        return $this->render('@TT/Security/user-tell-us.twig', $this->data);
    }

    public function checkUserEmailMD5($emails)
    {
        return $this->get('UserServices')->checkUserEmailMD5($emails);
    }

    /**
     * Sending reset email through checking user's email or username
     */
    public function resetPasswordAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $message = '';
        $success = true;
        $result  = $params  = array();

        $params['email']            = $post['EmailForgotForm'];
        $params['notificationTwig'] = 'emails/user-email-forgot-password.html.twig';
        $params['changePassLink']   = '/user/password/forgot/emails/';
        $params['tellUsLink']       = '/user/tell-us/emails/';
        $params['langCode']         = $this->LanguageGet();

        $reset = $this->get('UserServices')->userResetPassword($params);
        if (!$reset) {
            $success = false;
            $message = $this->translator->trans('Invalid credentials. Please try again.');
        } else {
            $success = (isset($reset['status']) && $reset['status'] == 'ok') ? true : false;
            $message = $reset['msg'];
        }

        $result['success'] = $success;
        $result['message'] = $message;

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Action in updating users password through AJAX call
     */
    public function updateForgotPasswordAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $emails    = $post['userId'];
        $user_info = $this->get('UserServices')->checkUserEmailMD5($emails);
        $userId    = $user_info['cu_id'];

        $newPass = $post['newPass'];
        $success = $this->get('UserServices')->updateUserPassword($userId, $newPass);
        $res     = new Response(json_encode($success));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    /**
     * Change Users password landing page
     *
     */
    public function changePasswordAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $user     = $this->getUser();
        $userId   = $user->getId();

        $userInfo = $this->get('UserServices')->getUserInfoById($userId, false);
        if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));

        $this->data['userId']      = $userId;
        $this->data['encodedPass'] = $userInfo[0]->getPassword();
        $this->data['saltPass']    = $userInfo[0]->getSalt();

        return $this->render('@TT/corporate/corporate-change-password.twig', $this->data);
    }

    /**
     * This function handles the change password AJAX call
     *
     */
    public function updatePasswordAction()
    {
        $request = $this->get('request');
        $post    = $request->request->all();

        $error = array();
        if ($this->get('app.sha256salted_encoderservices')->isPasswordValid($post['encodedPass'], $post['OldPassword'], $post['saltPass'])) {
            $result = $this->get('UserServices')->UpdateUserPassword($post['userId'], $post['NewPassword']);
        } else {
            $error['error'] = $this->translator->trans('Invalid Old Password. Please try again');
            $result         = json_encode($error);
        }

        $res = new Response($result);
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }
}
