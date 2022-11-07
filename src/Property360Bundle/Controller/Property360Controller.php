<?php

namespace Property360Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Property360Controller extends DefaultController
{

    public function getYourPropertyIN360Action($seotitle, $seodescription, $seokeywords)
    {
        $data_list = array();

        $action_text_display   = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
        $data_list['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

        $countries_dialing_codes              = $this->get('CmsCountriesServices')->getCountriesDialingCodes();
        $data_list['countries_dialing_codes'] = $countries_dialing_codes;
        $data_list['LanguageGet']             = $this->data['LanguageGet'];

        return $this->render('@Property360/property-360/request_360_form.twig', $data_list);
    }

    public function addPropertyIn360Action(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $action_text_display    = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
        $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

        $this->data['rating_value']  = trim($request->request->get('rating_value', ''));
        $this->data['city']          = trim($request->request->get('city', ''));
        $this->data['city_id']       = intval($request->request->get('city_id', 0));
        $this->data['country']       = trim($request->request->get('country', ''));
        $this->data['property_name'] = trim($request->request->get('property_name', ''));
        $this->data['contact_name']  = trim($request->request->get('contact_name', ''));
        $this->data['email']         = trim($request->request->get('email', ''));
        $this->data['dialing_code']  = trim($request->request->get('dialing_code', ''));
        $this->data['phone']         = trim($request->request->get('phone', ''));
        $this->data['msg']           = trim($request->request->get('msg', ''));

        $countries_dialing_codes = $this->get('CmsCountriesServices')->getCountriesDialingCodes();

        $this->data['countries_dialing_codes'] = $countries_dialing_codes;

        return $this->render('@Property360/property-360/add-property-in-360.twig', $this->data);
    }

    public function requestPropertyIN360Action(Request $request)
    {
        $Result = array();

        $property_info_list = json_decode(stripslashes($request->request->get('property_info_list', '')), true);

        $company           = $name              = trim($request->request->get('name', ''));
        $email             = trim($request->request->get('email', ''));
        $dialing_code      = trim($request->request->get('dialing_code', ''));
        $dialing_code_iso3 = trim($request->request->get('dialing_code_iso3', ''));
        $phone             = trim($request->request->get('phone', ''));
        $propertyType      = intval($request->request->get('propertyType', 1));
        $msg               = trim($request->request->get('msg', ''));
        $address           = trim($request->request->get('address', ''));
        $website           = trim($request->request->get('website', ''));

        if (sizeof($property_info_list) == 0 || $name == '' || $email == '' || $dialing_code == '' || $dialing_code_iso3 == '' || $phone == '' || strlen($name) > 50 || strlen($email) > 100 || strlen($msg)
            > 300 || strlen($phone) > 20) {
            $Result['msg']    = $this->translator->trans('Couldn\'t send your information. Please try again later');
            $Result['status'] = 'error';
            $res              = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if (!$this->get('app.utils')->check_email_address($email)) {
            $Result['msg']    = $this->translator->trans('Please enter a valid email');
            $Result['status'] = 'error';
            $res              = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $result_data = array();

        $contact_array             = array();
        $contact_array['company']  = $company;
        $contact_array['fullName'] = $name;
        $contact_array['email']    = $email;
        $contact_array['phone']    = $dialing_code.'-'.$phone;
        $contact_array['country']  = $dialing_code_iso3;
        $contact_array['message']  = $msg;

        $result_data['contact'] = $contact_array;

        foreach ($property_info_list as $item) {
            $properties_array = array();
            $property_name    = trim($item['property_name']);
            $country          = trim($item['country']);
            $country_iso3     = trim($item['country_iso3']);
            $city             = trim($item['city']);
            $city_id          = intval($item['city_id']);
            $rating_value     = intval($item['rating_value']);

            $properties_array['propertyType'] = $propertyType;
            $properties_array['propertyName'] = $property_name;
            $properties_array['country']      = $country_iso3;
            $properties_array['cityId']       = $city_id;
            $properties_array['city']         = $city;
            $properties_array['address']      = $address;
            $properties_array['rate']         = $rating_value;
            $properties_array['website']      = $website;

            $result_data['properties'][] = $properties_array;
        }

        $result_data_json = json_encode($result_data);

        $new_inquiry_api_url = $this->container->getParameter('modules')['hotels']['360_new_inquiry']['endpoint_url'];
        $response            = $this->get('app.utils')->send_data($new_inquiry_api_url, $result_data_json, \HTTP_Request2::METHOD_POST, null, array('Content-Type' => 'application/json'), array('connect_timeout' => 30));

        if ($response['response_status'] == 200) {
            $Result['msg']    = $this->translator->trans('Message sent!');
            $Result['status'] = 'ok';
        } else {
            $Result['msg']    = $this->translator->trans('Couldn\'t send your information. Please try again later');
            $Result['status'] = 'error';
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }
}
