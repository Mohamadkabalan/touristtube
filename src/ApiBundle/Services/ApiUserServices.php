<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class ApiUserServices {

    protected $em;
    protected $container;
    protected $utils;

    public function __construct( EntityManager $em, ContainerInterface $container, Utils $utils ) {
        $this->em = $em;
        $this->container     = $container;
        $this->utils         = $utils;
        $this->translator    = $this->container->get('translator');
    }

    public function tt_global_set($var, $val) {
        global $_tt_global_variables;
        $_tt_global_variables[$var] = $val;
    }

    public function tt_global_isset($var) {
        global $_tt_global_variables;
        return (isset($_tt_global_variables[$var]) && $_tt_global_variables[$var] != '') ? true : false;
    }

    public function tt_global_get($var) {
        global $_tt_global_variables;
        return isset($_tt_global_variables[$var]) ? $_tt_global_variables[$var] : false;
    }

    public function accountRegister( $options )
    {
        $success  = true;
        $return   = $messages = array();
        $options['cmsUserGroupId']   = $this->container->getParameter('ROLE_USER');

        if ( $options['yourEmail'] == '' )
        {
            $messages[] = $this->translator->trans('Please enter a valid email');
            $success    = false;
        }
        else if ( isset($options['yourEmail']) && $options['yourEmail'])
        {
            if ($this->container->get('UserServices')->checkDuplicateUserEmail($options['userId'], $options['yourEmail']))
            {
                $messages[] = $this->translator->trans('This email address belongs to an existing Tourist Tuber.');
                $success    = false;
            }
        }
        
        if (isset($options['yourUserName']) && $options['yourUserName'])
        {
            if ($this->container->get('UserServices')->checkDuplicateUserName($options['userId'], $options['yourUserName']))
            {
                $suggestedNewUserNames = $this->container->get('UserServices')->suggestUserNameNew($options['yourUserName']);
                $messages[]            = $this->translator->trans('Your username is already taken. try:').' '.implode(' or ', $suggestedNewUserNames);
                $success               = false;
            }
        }

        if ( !$options['yourPassword'] || !$options['password'] || $options['yourPassword'] != $options['password'] || $options['yourPassword'] == '' )
        {
            $messages[]            = $this->translator->trans('Invalid password.');
            $success               = false;
        }
        else if (strlen($options['yourPassword']) < 8)
        {
            $messages[]            = $this->translator->trans('Your password is too short.');
            $success               = false;
        }

        if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $options['yourPassword']))
        {
            $messages[]            = $this->translator->trans('Your password is not secure.');
            $success               = false;
        }

        if ( $options['yourUserName'] == '' ) {
            $options['yourUserName'] = $options['yourEmail'];
        }

        if ($success)
        {
            $insert = $this->container->get('UserServices')->generateUser( $options );

            if (is_array($insert) && isset($insert['error']) && !empty($insert['error'])) {
                $messages[] = $insert['error'];
                $success    = false;
            } else {
                $this->container->get('UserServices')->sendRegisterActivationEmail(array('userId' => $insert->getId(), 'activationLink' => '/users/activate-account/', 'langCode' => $options['lang'] ) );
                $messages[] = 'An email is sent to you to activate your account.';
            }
        }
        $return['status'] = ( !$success )?'error':'ok';
        $return['success'] = $success;
        $return['message'] = (!empty($messages) && $messages) ? implode(' ', $messages) : $messages;
        return $return;
    }

    public function updateAccountInfo( $save )
    {
        $user_id = intval($save['id']);
        $email = $save['yourEmail'];
        $return   = array();
        
        if( $user_id != 0 )
        {
            $return['success'] = true;
            $return['status'] = 'ok';

            if (!$this->utils->check_email_address($email) || $email == '' )
            {
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans('Please enter a valid email');
            } 
            else if ( $this->container->get('UserServices')->checkDuplicateUserEmail($user_id, $email) )
            {
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans('This email address belongs to an existing Tourist Tuber;'). " ".$this->translator->trans("Please sign in using this email or reset your password");
            }
            else 
            {
                $success = $this->container->get('UserServices')->modifyUser($save);
                if (is_array($success) && isset($success['error']) && !empty($success['error'])) {
                    $return['status'] = 'error';
                    $return['message'] = $this->translator->trans('Couldn\'t save information. Please try again later');
                } else{
                    $return['message'] = $this->translator->trans('Account information saved!');
                }                
            }
            
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Please login to complete this task.");
        }
        return $return;
    }

    public function getUserInfoById( $user_id, $lang )
    {
        $return   = array();
        $Results = array();
        if( $user_id != 0 )
        {
            $userInfo = $this->container->get('UserServices')->getUserInfoById( $user_id );
            if( $userInfo )
            {
                $Results['id'] = $userInfo['cu_id'];
                $Results['username'] = (!$userInfo['cu_yourusername'])? '':$userInfo['cu_yourusername'];
                $Results['firstName'] = (!$userInfo['cu_fname'])? '':$this->utils->htmlEntityDecode($userInfo['cu_fname']);
                $Results['lastName'] = (!$userInfo['cu_lname'])? '':$this->utils->htmlEntityDecode($userInfo['cu_lname']);
                $Results['fullName'] = $this->utils->returnUserArrayDisplayName( $userInfo );
                $Results['website'] = (!$userInfo['cu_websiteUrl'])? '':$this->utils->htmlEntityDecode($userInfo['cu_websiteUrl']);
                $Results['description'] = (!$userInfo['cu_smallDescription'])? '':$this->utils->htmlEntityDecode($userInfo['cu_smallDescription']);

                $dob = ( !$userInfo['cu_yourbday'] || $userInfo['cu_yourbday'] == '0000-00-00' )? '':$userInfo['cu_yourbday']->format('M d, Y');

                $Results['birth'] = $dob;
                $Results['hometown'] = (!$userInfo['cu_hometown'])? '':$this->utils->htmlEntityDecode($userInfo['cu_hometown']);
                $Results['city'] = (!$userInfo['w_name'])? '':$userInfo['w_name'];
                $Results['cityId'] = (!$userInfo['cu_cityId'])? 0:$userInfo['cu_cityId'];
                $Results['country'] = (!$userInfo['cu_yourcountry'])? '':$userInfo['cu_yourcountry'];
                $Results['countryName'] = (!$userInfo['c_name'])? '':$userInfo['c_name'];
                $Results['gender'] = (!$userInfo['cu_gender'])? 'O':$userInfo['cu_gender'];
                $Results['email'] = (!$userInfo['cu_youremail'])? '':$userInfo['cu_youremail'];
                $Results['employment'] = (!$userInfo['cu_employment'])? '':$this->utils->htmlEntityDecode($userInfo['cu_employment']);
                $Results['education'] = (!$userInfo['cu_highEducation'])? '':$this->utils->htmlEntityDecode($userInfo['cu_highEducation']);
                $Results['interestedIn'] = (!$userInfo['cu_intrestedIn'])? 0:$userInfo['cu_intrestedIn'];
                
                $Results['image'] = $userInfo['cu_profilePic'];
                if( $Results['image']!='' && $Results['image'] )
                {
                    $Results['image'] = 'media/tubers/'.$Results['image'];
                }
                else
                {
                    $Results['image'] = 'media/images/tuber/change_img_logo.png';
                }

                $return['success'] = true;
                $return['status'] = 'ok';
            } else {
                $return['success'] = false;
                $return['status'] = 'error';
                $return['message'] = $this->translator->trans("Couldn't process. Please try again later");
            }
        } else {
            $return['success'] = false;
            $return['status'] = 'error';
            $return['message'] = $this->translator->trans("Please login to complete this task.");
        }

        $return['info']    = $Results;
        $return['interestedIn'] = $this->container->get('UserServices')->getInterestedInList( $lang )['data'];

        return $return;
    }

    public function mobileIsLogged($login_token) {

        if (!isset($login_token) || empty($login_token) || !$login_token) {
            return 0;
        }

        $userRep = $this->em->getRepository('TTBundle:CmsTubers')->loginUserToken($login_token);
//         print_r($userRep[0]['userId']);exit;
		
		if (!$userRep) return 0;

		$userInfo = $this->container->get('UserServices')->userSetSession( $userRep[0]['userId'] );
                $this->tt_global_set('userInfo', $userInfo);
		
		if ($userRep[0]['keepMeLogged'] == "1")
		{
			$id = $userRep[0]['id'];
			$userUpdateRep = $this->em->getRepository('TTBundle:CmsTubers')->keepMeLooged($userRep[0]['id']);
		}
		
		$this->tt_global_set('isLogged', true);
		
		return $userRep[0]['userId'];
    }

    public function isUserLoggedIn() {

        global $request;
        if ($this->tt_global_isset('isLogged')) {
            return $this->tt_global_get('isLogged');
        }

        $lt_cookie = $request->cookies->get('lt', '');
        if (!isset($lt_cookie) || empty($lt_cookie)) {
            $this->tt_global_set('isLogged', false);
            return false;
        }
        $login_token = $lt_cookie;

        $userLogin = $this->em->getRepository('TTBundle:CmsTubers')->userLoggedCheck($login_token);
        $row = $userLogin;

        if (sizeof($row) > 0) {
            $row = $row[0];

            $userInfo = $this->container->get('UserServices')->userSetSession( $row->getUserId() );
            $this->tt_global_set('userInfo', $userInfo);

            if ($row->getKeepMeLogged() == 1) {
                $keepMeLooged = $this->em->getRepository('TTBundle:CmsTubers')->keepMeLooged($row->getId());
            }
            $this->tt_global_set('isLogged', true);
            return true;
        } else {
            $this->tt_global_set('isLogged', false);
            return false;
        }
    }

    public function userEndSession($uid) {

        $endSession = $this->em->getRepository('TTBundle:CmsTubers')->endingSession($uid);
        return $endSession;
    }

}
