<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TuberController extends DefaultController {

    public function accountInfoAction() {

        if ($this->data['userIsLogged'] == 0) {
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
        if($this->data['profile_pic']!='') $this->data['profile_pic'] = '/media/tubers/'.$this->data['profile_pic'];
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
        $this->data['interestedInList'] = $this->get('UserServices')->getInterestedInList();

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

//        $this->debug($userInfo);
        return $this->render('account/account-info.twig', $this->data);
    }
    
    public function accountSettingsAction() {

        if ($this->data['userIsLogged'] == 0) {
            return $this->redirectToLangRoute('_log_in', array(), 301);
        }

        $userInfo = $this->get('UserServices')->getUserInfoById($this->data['USERID']);
        $this->data['uname'] = $userInfo['cu_yourusername'];

        $this->data['notification_email'] = 1;
        $notifications = $this->get('UserServices')->getNotificationsEmails($userInfo['cu_youremail']);
        if($notifications){
            $this->data['notification_email'] = $notifications['n_notify'];
        }

        return $this->render('account/account-settings.twig', $this->data);
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
                $Result['msg'] = $this->translator->trans('Couldnt save password. Please try again later.');
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
            if (!$this->get('app.utils')->check_email_address($email)) {
                $err .= $this->translator->trans('Please enter a valid email'). "<br />";
            } else if (!$this->get('UserServices')->userEmailisUnique($user_id, $email)) {
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
    
    public function accountSharingAction() {
        return $this->render('account/account-sharing.twig', $this->data);
    }
}
