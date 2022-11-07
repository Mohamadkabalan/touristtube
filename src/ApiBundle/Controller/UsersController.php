<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersController extends DefaultController {

    public function __construct() {
        
    }

    public function accountRegisterAction()
    {
        $request = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());
        $email = ($criteria['email'])?$criteria['email']:'';
        $username = ($criteria['username'])?$criteria['username']:'';
        $password = ($criteria['password'])?$criteria['password']:'';
        $confirmPassword = ($criteria['confirmPassword'])?$criteria['confirmPassword']:'';

        $options = array
        (
            'yourEmail' => $email,
            'userId' => 0,
            'yourUserName' => $username,
            'fullName' => $username,
            'fname' => $username,
            'defaultPublished' => 0,
            'lang' => $this->getLanguage(),
            'yourPassword' => $password,
            'password' => $confirmPassword
        );

        $resp = $this->get('ApiUserServices')->accountRegister( $options );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function accountInfoAction()
    {
        $user_id = $this->getUserId();
        $resp = $this->get('ApiUserServices')->getUserInfoById( intval($user_id), $this->getLanguage() );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }

    public function updateAccountInfoAction()
    {
        $request = Request::createFromGlobals();
        $criteria = array_merge($request->request->all(), $request->query->all());
        $user_id = $this->getUserId();

        $gender = ($criteria['gender'])?$criteria['gender']:'O';
        $fname = ($criteria['firstName'])?$criteria['firstName']:'';
        $lname = ($criteria['lastName'])?$criteria['lastName']:'';
        $username = ($criteria['username'])?$criteria['username']:'';
        $birthday = ($criteria['birth'])?$criteria['birth']:'';
        $email = ($criteria['email'])?$criteria['email']:'';
        $employment = ($criteria['employment'])?$criteria['employment']:'';
        $high_education = ($criteria['education'])?$criteria['education']:'';
        $intrested_in = ($criteria['interestedIn'])?intval($criteria['interestedIn']):0;
        $hometown = ($criteria['hometown'])?$criteria['hometown']:'';
        $country = ($criteria['countryCode'])?$criteria['countryCode']:'';
        $city_id = ($criteria['cityId'])?intval($criteria['cityId']):0;
        $city = ($criteria['city'])?$criteria['city']:'';
        $website = ($criteria['website'])?$criteria['website']:'';
        $website = str_replace('http://', '', $website);
        $description = ($criteria['description'])?$criteria['description']:'';

        $save['id'] = $user_id;
        $save['gender'] = $gender;
        $save['fname'] = $fname;
        $save['lname'] = $lname;
        $save['yourUserName'] = $username;
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
        
        $resp = $this->get('ApiUserServices')->updateAccountInfo( $save );

        $res = $this->convertToJson($resp);
        if ($res == "") {
            return "";
        }
        return $res;
    }
}