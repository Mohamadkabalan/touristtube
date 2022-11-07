<?php

namespace ApiBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class UserServices {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
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

    public function mobileIsLogged($login_token) {

        if (!isset($login_token) || empty($login_token) || !$login_token) {
            return 0;
        }

        $userRep = $this->em->getRepository('TTBundle:CmsTubers')->loginUserToken($login_token);
//         print_r($userRep[0]['userId']);exit;
		
		if (!userRep)
			return 0;
		
        $this->userSetSession(NULL, $userRep[0]['userId']);
		
		if ($userRep[0]['keepMeLogged'] == "1")
		{
			$id = $userRep[0]['id'];
			$userUpdateRep = $this->em->getRepository('TTBundle:CmsTubers')->keepMeLooged($userRep[0]['id']);
		}
		
		$this->tt_global_set('isLogged', true);
		
		return $userRep[0]['userId'];
    }

    public function userIsLogged() {

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

        $userLogin = $this->em->getRepository('TTBundle:CmsTubers')->userLoggedCheck();
        $row = $userLogin;

        if (sizeof($row) > 0) {
            $row = $row[0];
            $this->userSetSession(NULL, $row->getUserId());

            if ($row->getKeepMeLogged() == 1) {
                $keepMeLooged = $this->em->getRepository('TTBundle:CmsTubers')->keepMeLooged();
            }
            $this->tt_global_set('isLogged', true);
            return true;
        } else {
            $this->tt_global_set('isLogged', false);
            return false;
        }
    }

    public function userSetSession($row, $userid = NULL) {
        if (!$this->tt_global_isset('userInfo')) {
            if ($userid) {
                $setSession = $this->em->getRepository('TTBundle:CmsUsers')->settingSession($userid);
//             print_r($setSession[0]['id']);exit;

                if (sizeof($setSession) == 0) {
                    return false;
                }
            }

            $userInfo = array(
                'id' => $setSession[0]['id'],
                'YourUserName' => $setSession[0]['yourusername'],
                'FullName' => $setSession[0]['fullname'],
                'fname' => $setSession[0]['fname'],
                'display_fullname' => $setSession[0]['displayFullname'],
                'profile_Pic' => $setSession[0]['profilePic'],
                'email' => $setSession[0]['email'],
                'isChannel' => $setSession[0]['ischannel']
                    //'current_channel' => $row['isChannel'] ? userDefaultChannelGet($row['id']) : false
            );
            $this->tt_global_set('userInfo', $userInfo);
        }
    }

    public function userEndSession($uid) {

        $endSession = $this->em->getRepository('TTBundle:CmsTubers')->endingSession($uid);
        return $endSession;
    }

}
