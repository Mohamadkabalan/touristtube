<?php

namespace TTBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Doctrine\ORM\NoResultException;
use Symfony\Component\DomCrawler\Crawler;
use PaymentBundle\Model\Payment;
use \TTBundle\Model\ElasticSearchSC;

//use Chewett\UglifyJS2\JSUglify2;
//use usr\bin\node\uglifyjs;

class DefaultController extends Controller
{
    public $on_production_server               = true;
    public $data                               = array();
    public $_tt_global_variables               = array();
    public $translator;
    public $max_api_call_attempts              = 3; // this includes the first call, and any subsequent call(s) due to failure
    public $pause_between_retries_us           = 500000; // number of micro seconds to pause between retries in case of failure
    public $user_pin_validation_mode           = true;
    public $show_deals_block                   = 0;
    public $show_flights_block                 = 1;
    public $show_flights_corporate_block       = 1;
    public $show_flights_multiple_destinations = 1;
    public $storage_engine                     = '';







    public function __construct()
    {
        
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->containerInitialized();

        global $request;
        global $accountId;
        global $userId;
        global $userGroupId;
        global $profileId;
        global $profileName;
        global $profileLevel;
        global $menuListArr;
        global $allowAccessSubAccounts;
        global $allowAccessSubAccountsUsers;
        global $allowApproval;


        $userObj                                  = $this->get('UserServices')->getUserDetails(array('id' => $this->userGetID()));


        $accountId    = $userObj[0]['cu_corpoAccountId'];
        $userId       = $userObj[0]['cu_id'];
        $userGroupId  = $userObj[0]['cu_cmsUserGroupId'];
        $profileId    = $userObj[0]['profileId'];
        $profileName  = $userObj[0]['profileName'];
        $profileLevel = $userObj[0]['profileLevel'];
        $allowAccessSubAccounts = $userObj[0]['cu_allowAccessSubAcc'];
        $allowAccessSubAccountsUsers = $userObj[0]['cu_allowAccessSubAccUsers'];
        $allowApproval = $userObj[0]['cu_allowApproval'];

    }

    private function containerInitialized()
    {
        $this->on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');

        if ($this->container->hasParameter('MAX_API_CALL_ATTEMPTS')) $this->max_api_call_attempts = $this->container->getParameter('MAX_API_CALL_ATTEMPTS');

        if ($this->container->hasParameter('PAUSE_BETWEEN_RETRIES_US')) $this->pause_between_retries_us = $this->container->getParameter('PAUSE_BETWEEN_RETRIES_US');

        if ($this->container->hasParameter('SHOW_DEALS_BLOCK')) $this->show_deals_block         = $this->container->getParameter('SHOW_DEALS_BLOCK');
        $this->data['SHOW_DEALS_BLOCK'] = $this->show_deals_block;

        if ($this->container->hasParameter('SHOW_FLIGHTS_BLOCK')) $this->show_flights_block         = $this->container->getParameter('SHOW_FLIGHTS_BLOCK');
        $this->data['SHOW_FLIGHTS_BLOCK'] = $this->show_flights_block;

        if ($this->container->hasParameter('SHOW_FLIGHTS_MULTIPLE_DESTINATIONS')) $this->show_flights_multiple_destinations         = $this->container->getParameter('SHOW_FLIGHTS_MULTIPLE_DESTINATIONS');
        $this->data['SHOW_FLIGHTS_MULTIPLE_DESTINATIONS'] = $this->show_flights_multiple_destinations;

        if ($this->container->hasParameter('STORAGE_ENGINE')) $this->storage_engine         = $this->container->getParameter('STORAGE_ENGINE');
        $this->data['STORAGE_ENGINE'] = $this->storage_engine;

        $this->translator = $this->get('translator');

        global $request;
        global $GLOBAL_LANG;
        //moz api---------------------------------------------------
        // Get your access id and secret key here: https://moz.com/products/api/keys
        //        $accessID = "mozscape-9663c516dd";
        //        $secretKey = "56830b2f2b166aee2eef5eb93ffaa039";
        //        // Set your expires times for several minutes into the future.
        //        // An expires time excessively far in the future will not be honored by the Mozscape API.
        //        $expires = time() + 300;
        //
         //        // Put each parameter on a new line.
        //        $stringToSign = $accessID."\n".$expires;
        //        // Get the "raw" or binary output of the hmac hash.
        //        $binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
        //
         //        // Base64-encode it and then url-encode that.
        //        $urlSafeSignature = urlencode(base64_encode($binarySignature));
        //
         //        // Specify the URL that you want link metrics for.
        //        $objectURL = "https://www.touristtube.com";
        //        // Add up all the bit flags you want returned.
        //        // Learn more here: https://moz.com/help/guides/moz-api/mozscape/api-reference/url-metrics
        //        $cols = "103079215108";
        //
         //        // Put it all together and you get your request URL.
        //        // This example uses the Mozscape URL Metrics API.
        //        $requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/".urlencode($objectURL)."?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;
        //
         //        // Use Curl to send off your request.
        //        $options = array(
        //            CURLOPT_RETURNTRANSFER => true
        //	);
        //        $ch = curl_init($requestUrl);
        //        curl_setopt_array($ch, $options);
        //        $content = curl_exec($ch);
        //        curl_close($ch);
        //print_r($content);
        //end moz api---------------------------------------------------

        $allowed_lang_array     = array('fr', 'in', 'cn');
        $subdomain_lang         = explode("/", $request->server->get('REQUEST_URI', ''));
        $i                      = 0;
        $GLOBAL_LANG            = '';
        $max_uri_parts_to_parse = 3;

        while ($i < sizeof($subdomain_lang) && $i < $max_uri_parts_to_parse) {
            if (!$subdomain_lang[$i]) {
                $max_uri_parts_to_parse++;
                $i++;
                continue;
            }

            if (in_array($subdomain_lang[$i], $allowed_lang_array)) {
                $GLOBAL_LANG = $subdomain_lang[$i];
                break;
            }

            $i++;
        }

        $this->data['isUserLoggedIn'] = 0;
        $this->data['LanguageGet']    = $this->LanguageGet();
        if ($this->get('ApiUserServices')->isUserLoggedIn()) {
            $this->data['isUserLoggedIn']  = 1;
            $this->data['userProfile']     = $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/myprofile');
            $this->data['userName']        = $this->userGetName();
            $this->data['userNameWelcome'] = $this->translator->trans('Welcome').' '.$this->data['userName'];
        }

        $lang_arraydata = array();
        $ur_arraydata   = '';
        $ur_array       = $this->get('TTRouteUtils')->UriCanonicalPageURLForLG();
        $langarray      = $this->get('TTServices')->getLanguagesList($this->data['isUserLoggedIn']);

        $this->data['languageISO2Code'] = 'en';
        foreach ($langarray as $lang_items) {
            $langitem             = array();
            $lang_key             = $lang_items['l_code'];
            $lang_val             = $lang_items['l_webCode'];
            $lang_name            = $lang_items['l_label'];
            if ($lang_key != 'en') $langUrl              = $ur_array[0].$ur_array[1].'/'.$lang_val.'/'.$ur_array[2];
            else $langUrl              = $ur_array[0].$ur_array[1].'/'.$ur_array[2];
            $ur_arraydata         .= '<link rel="alternate" hreflang="'.$lang_key.'" href="'.$langUrl.'"/>';
            $langitem['selected'] = 0;
            if (in_array($this->data['LanguageGet'], array($lang_key, $lang_val))) {
                $langitem['selected']           = 1;
                $this->data['languageISO2Code'] = $lang_key;
            }
            $langitem['lang_key'] = $lang_key;
            $langitem['lang_val'] = $lang_val;
            $langitem['link']     = $langUrl;
            $langitem['name']     = $lang_name;
            $lang_arraydata[]     = $langitem;
        }
        $this->data['ur_arraydata']   = $ur_arraydata;
        $this->data['lang_arraydata'] = $lang_arraydata;

        $this->data['subdomain_suffix'] = ( $this->container->hasParameter('subdomain_suffix') ) ? $this->container->getParameter('subdomain_suffix') : '';
        $this->data['use_cookie']       = 0;
        if (isset($_COOKIE["use_cookie"]) && $_COOKIE["use_cookie"]) {
            $this->data['use_cookie'] = 1;
        } else if (isset($_COOKIE["use_cookie"])) {
            $this->data['use_cookie'] = -1;
        }
        $this->data['popupOffers']            = 0;
        //	if( !isset($_COOKIE["popupOffers"]) ){
        //	    setcookie("popupOffers", 1, time()+43200);
        //	    $this->data['popupOffers'] = 1;
        //	}
        $this->data['input']                  = array();
        $this->data['nearbyAttraction']       = array();
        $this->data['base_dir']               = realpath($this->container->getParameter('kernel.root_dir').'/..');
        $this->data['webview']                = "";
        $this->data['canonicallink']          = "";
        $this->data['canonicalprevlink']      = "";
        $this->data['canonicalnextlink']      = "";
        $this->data['subdomain_link']         = "";
        $this->data['needpayment']            = 0;
        $this->data['is_corporate_account']   = $this->userIsCorporateAccount();
        // on GCP::
        // SQLSTATE[HY000]: General error: 1456 Recursive limit 0 (as set by the max_sp_recursion_depth variable) was exceeded for routine proc_json_internal_sub_corporate_accounts
        $this->data['corporate_sub_accounts'] = array(); // $this->getCorporateSubAccounts();
        $this->data['datapagename']           = '';
        $this->data['pageBannerImage']        = '';
        $this->data['pageBannerPano']         = 'default';
        $this->data['pageBannerH2']           = '';
        $this->data['pageBannerH3']           = '';
        $USERID                               = $this->userGetID();
        $this->data['USERID']                 = $USERID;
        $this->data['USEREMAIL']=$this->getUserEmail();
        $this->data['OriginalUrl']            = $this->get('TTRouteUtils')->UriCurrentPageURL();
        $this->data['searchfortext']          = $this->translator->trans('What are you looking for?');
        $this->data['seotitle']               = '';
        $this->data['seokeywords']            = '';
        $this->data['seodescription']         = '';
        $routeClean                           = $route                                = urldecode($ur_array[2]);
        $this->data['route']                  = $route;
        //$route = $this->get('app.utils')->cleanTitleData($route);
        $aliasSeo                             = $this->get('TTServices')->getAliasSeo($route, $this->data['LanguageGet']);

        $this->data['aliasseo'] = $aliasSeo;
        if ($this->data['aliasseo']) {
            $this->data['route']          = $route                        = $routeClean;
            $title                        = ($this->data['aliasseo']['ml_title']) ? $this->data['aliasseo']['ml_title'] : $this->data['aliasseo']['t_title'];
            $description                  = ($this->data['aliasseo']['ml_description']) ? $this->data['aliasseo']['ml_description'] : $this->data['aliasseo']['t_description'];
            $keywords                     = ($this->data['aliasseo']['ml_keywords']) ? $this->data['aliasseo']['ml_keywords'] : $this->data['aliasseo']['t_keywords'];
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($title);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($keywords);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($description);
        }
        
        $this->data['upload_link']     = $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/upload');
        $this->data['isindexpage']     = 0;
        $this->data['hidefirstsearch'] = 0;
        $this->data['is_owner']        = 0;
        $this->data['switchToTxt']     = $this->translator->trans('switch to');

        $this->data['showfooter']                  = 1;
        $this->data['showHeaderSearch']            = 1;
        $this->data['fbimg']                       = 'https://www.touristtube.com/media/images/Logo.png';
        $this->data['fbdesc']                      = '';
        $this->data['tmzone']                      = $this->checkTimeZoneCookie();
        $this->data['hotelblocksearchIndex']       = 0;
        $this->data['flightblocksearchIndex']      = 0;
        $this->data['hotelflightblocksearchIndex'] = 0;
        $this->data['dealblocksearchIndex']        = 0;
        $this->data['hideblocksearchButtons']      = 0;
        $this->data['hideblocksearch']             = 0;
        //if ($this->data['USERID'] == 3798) {

        $selectedCurrency = filter_input(INPUT_COOKIE, 'currency');

        $this->data['selected_currency'] = ($selectedCurrency == "") ? "USD" : $selectedCurrency;
        $this->data['currencies']        = $this->get('CurrencyService')->listCurrencies();
        $this->data['currency_symbol']   = $this->get('CurrencyService')->getCurrencySymbol($this->data['selected_currency']);
        $this->data['MAP_KEY']           = '';
        $pagehttp                        = $this->get('TTRouteUtils')->getUriPageURLHTTP();
        if ($pagehttp == 'https') $this->data['MAP_KEY']           = '&key='.$this->container->getParameter('MAP_KEY')[0];
    }

    public function checkDBAction()
    {
		$httpResponse = new Response();
		
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        
        try
		{
            $statement = $connection->query('SELECT CURRENT_TIMESTAMP AS ct');            
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            // echo $result['ct'];
			
			$httpResponse->setStatusCode(200);
			$httpResponse->setContent(1);
        }
		catch (\Exception $e)
		{
            $httpResponse->setStatusCode(500);
			$httpResponse->setContent(0);
        }
		
        return $httpResponse;
    }

    public function testAction()
    {
        if ($this->container->hasParameter('MACHINE_NAME') && !in_array($this->container->getParameter('MACHINE_NAME'), array('tt', 'staging1', 'demo')))
                return $this->render('default/test.twig', $this->data);

        $transactionId = 'D52C3FBE-6834-48EC-A5DB-EF19FE632301';
        /*
         * $payment = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneByUuid($transactionId);
         * if ($payment)
         * {
         * $paymentTransactionFeedbackList = $payment->getPaymentTransactionFeedbackList();
         * if ($paymentTransactionFeedbackList)
         * {
         * foreach ($paymentTransactionFeedbackList as $paymentTransactionFeedback)
         * {
         * $this->debug('merchant_reference:: '.$paymentTransactionFeedback->getMerchantReference());
         * }
         * }
         * else
         * {
         * $this->debug('paymentTransactionFeedbackList empty!');
         * }
         * }
         * else
         * {
         * $this->debug('No payment record found for transaction_id:: '.$transaction_id);
         * }
         */

        $merchantReference = 'C2B-2345a3303a66a4e6';

        // $ptf = $this->getDoctrine()->getRepository('PaymentBundle:PaymentTransactionFeedback')->findBy(array('merchantReference' => $merchantReference));
        $ptf = $this->getDoctrine()->getRepository('PaymentBundle:PaymentTransactionFeedback')->findOneBy(array('merchantReference' => $merchantReference));
        if ($ptf) {
            // $ptf = $ptf[0];
            // $p = $ptf->getPayment();
            $p = null;
            if ($p) {
                $this->debug('Found UUID:: '.$p->getUuid().', PNR:: '.$p->getPassengerNameRecord()->getPnr());
            } else {
                $this->debug('No payment record found for merchant_reference:: '.$merchantReference);
            }
        } else {
            $this->debug('No ptf found for merchant_reference:: '.$merchantReference);
        }

        $merchantReference = '986-9FE5a3303e31fdb4';

        $p = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneBy(array('merchantReference' => $merchantReference));
        if ($p) {
            $this->debug('Found UUID from payment:: '.$p->getUuid());
        } else {
            $this->debug('No payment record found for merchant_reference:: '.$merchantReference);
        }

        $merchantReference = '986-9FE5a3303e31fdb4';
        $merchantReference = '46E-98A5a4a4616bc337';

        $p = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneBy(array('merchantReference' => $merchantReference));
        if ($p) {
            $this->debug('Found UUID:: '.$p->getUuid());

            //
        } else {
            $this->debug('No payment record found for merchant_reference:: '.$merchantReference);
        }

        $merchantReference = '986-9FE5a3303e31fdb4';

        $p = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneBy(array('merchantReference' => $merchantReference));
        if ($p) {
            $this->debug('Found UUID from payment:: '.$p->getUuid());
        } else {
            $this->debug('No payment record found for merchant_reference:: '.$merchantReference);
        }

        $merchantReference = '986-9FE5a3303e31fdb4';
        $merchantReference = '46E-98A5a4a4616bc337';

        $p = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneBy(array('merchantReference' => $merchantReference));
        if ($p) {
            $this->debug('Found UUID:: '.$p->getUuid());

            //
        } else {
            $this->debug('No payment record found for merchant_reference:: '.$merchantReference);
        }

        $masked_card_number = '538032******9267';
        $this->debug('Masked Card Number Hash:: '.sha1($masked_card_number, false));

        $merchantReference = 'BA1-2D75a4bae18c6e26';

        $ptf = $this->getDoctrine()->getRepository('PaymentBundle:PaymentTransactionFeedback')->findOneBy(array('merchantReference' => $merchantReference));
        if ($ptf) {
            // $p = $ptf->getPayment();
            $p = null;
            if ($p) {
                $this->debug('Found UUID:: '.$p->getUuid().', PNR:: '.$p->getPassengerNameRecord()->getPnr());

                $request = $this->getRequest();

                $update_ptf = $request->query->get('update_ptf', 0);
                if ($update_ptf) {
                    $this->debug('Saving ptf.paymentUUID w/ '.$p->getUuid());

                    $ptf->setPaymentUUID($p->getUuid());

                    $this->getDoctrine()->getManager()->flush();
                }
            } else {
                $this->debug('ptf-to-p No payment record found for merchant_reference:: '.$merchantReference);

                $p = $this->getDoctrine()->getRepository('PaymentBundle:Payment')->findOneBy(array('merchantReference' => $merchantReference));
                if ($p) {
                    $this->debug('Found UUID:: '.$p->getUuid().', PNR:: '.$p->getPassengerNameRecord()->getPnr());
                    $this->debug('Saving ptf.paymentUUID w/ '.$p->getUuid());

                    $ptf->setPaymentUUID($p->getUuid());

                    $this->getDoctrine()->getManager()->flush();
                } else $this->debug('No payment record found for merchant_reference:: '.$merchantReference);
            }
        } else $this->debug('No ptf found for merchant_reference:: '.$merchantReference);

        // return $this->render('default/test.twig', $this->data);


        $sub_accounts = $this->getCorporateSubAccounts(false);
        if ($sub_accounts) {
            $this->debug('concatenate_values w/o delimiter:: '.$this->get('app.utils')->concatenate_values(array(
                    $sub_accounts[0]['name'], $sub_accounts[0]['email'])));
            $this->debug('concatenate_values using a delimiter:: '.$this->get('app.utils')->concatenate_values(array(
                    $sub_accounts[0]['name'], $sub_accounts[0]['email']), array('delimiter' => ' ')));

            $this->debug('concatenate_values using field-specific delimiter specs:: '.$this->get('app.utils')->concatenate_values(array(
                    'name' => $sub_accounts[0]['name'], 'email' => $sub_accounts[0]['email']), array('delimiter' => ' ', 'delimiter_specs' => array('email' => array(
                            'start_delimiter' => '(', 'end_delimiter' => ')')))));
            $this->debug('concatenate_values using field-specific delimiter specs, using prepend_global_delimiter:: '.$this->get('app.utils')->concatenate_values(array(
                    'name' => $sub_accounts[0]['name'], 'email' => $sub_accounts[0]['email']), array('delimiter' => ' ', 'delimiter_specs' => array('email' => array(
                            'start_delimiter' => '(', 'end_delimiter' => ')', 'prepend_global_delimiter' => true)))));


            $this->debug('sub_accounts:: '.print_r($sub_accounts, true));

            $this->debug('sub_accounts as JSON:: '.json_encode($sub_accounts));

            $sub_account_ids = array();
            // $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('keys_from_field' => 'id', 'key_to_child_elements' => 'sub_accounts', 'sort_keys' => true, 'sort_flag' => SORT_NUMERIC));
            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('keys_from_field' => 'id', 'key_to_child_elements' => 'children',
                'sort_keys' => true, 'sort_flag' => SORT_NUMERIC));

            $this->debug('sub_account_ids[w/ keys_from_field:: id, key_to_child_elements, sorting set to numeric]:: '.print_r($sub_account_ids, true));

            $sub_account_ids = array();
            // $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('keys_from_field' => 'id', 'key_to_child_elements' => 'sub_accounts', 'associative_result' => true, 'values_from_key' => 'name', 'sort_keys' => true));
            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('keys_from_field' => 'id', 'key_to_child_elements' => 'children',
                'associative_result' => true, 'values_from_key' => 'name', 'sort_keys' => true));
            $this->debug('sub_account_ids[w/ keys_from_field:: id, key_to_child_elements, associative_result:: true, sorted values]:: '.print_r($sub_account_ids, true));

            // $this->get('app.utils')->convert_fields($sub_accounts, array('modify_reference' => true, 'key_to_child_elements' => 'sub_accounts',
            $this->get('app.utils')->convert_fields($sub_accounts, array('modify_reference' => true, 'key_to_child_elements' => 'children',
                'field_conversion_specs' => array(
                    array('field_name' => 'text', 'function_name' => 'concatenate_values',
                        'arguments' => array(
                            array('name' => 'name', 'email' => 'email'),
                            array('dummy_argument' => true, 'delimiter' => ' ', 'delimiter_specs' => array(
                                    'email' => array('start_delimiter' => '(', 'end_delimiter' => ')',
                                        'prepend_global_delimiter' => true)))
                        )
                    )
                // , array('function_name' => 'copy_array_value', 'arguments' => array('data_array', 'sub_accounts', 'children'), 'forced_literals' => array('sub_accounts'))
                )
            ));

            $this->debug('sub_accounts (in non-associative form) processed w/ convert_fields:: '.print_r($sub_accounts, true));
            $this->debug('sub_accounts (in non-associative form) processed w/ convert_fields as JSON:: '.json_encode($sub_accounts));
        }

        $sub_accounts = $this->getCorporateSubAccounts();
        if ($sub_accounts) {
            $this->debug('sub_accounts (associative_result):: '.print_r($sub_accounts, true));


            $sub_account_ids = array();

            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids);
            $this->debug('sub_account_ids[no key_to_child_elements provided]:: '.print_r($sub_account_ids, true));

            $sub_account_ids = array();

            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('sort_keys' => true, 'sort_flag' => SORT_NUMERIC));
            $this->debug('sub_account_ids[no key_to_child_elements provided, sorting set to numeric]:: '.print_r($sub_account_ids, true));



            $sub_account_ids = array();

            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('key_to_child_elements' => 'sub_accounts'));
            $this->debug('sub_account_ids[w/ key_to_child_elements, no sorting]:: '.print_r($sub_account_ids, true));

            $sub_account_ids = array();
            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('key_to_child_elements' => 'sub_accounts', 'sort_keys' => true,
                'sort_flag' => SORT_NUMERIC));
            $this->debug('sub_account_ids[w/ key_to_child_elements, sorting set to numeric]:: '.print_r($sub_account_ids, true));


            // get_key_set with associative_result:: true and values_from_key:: name
            $sub_account_ids = array();

            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('associative_result' => true, 'values_from_key' => 'name'));
            $this->debug('sub_account_ids[w/o key_to_child_elements, associative_result:: true and values_from_key:: name]:: '.print_r($sub_account_ids, true));

            $sub_account_ids = array();

            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('sort_keys' => true, 'associative_result' => true, 'values_from_key' => 'name'));
            $this->debug('sub_account_ids[w/o key_to_child_elements, sorted values, associative_result:: true and values_from_key:: name]:: '.print_r($sub_account_ids, true));



            $sub_account_ids = array();

            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('key_to_child_elements' => 'sub_accounts', 'associative_result' => true,
                'values_from_key' => 'name'));
            $this->debug('sub_account_ids[w/ key_to_child_elements, no sorting, associative_result:: true and values_from_key:: name]:: '.print_r($sub_account_ids, true));

            $sub_account_ids = array();
            // $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('key_to_child_elements' => 'sub_accounts', 'sort_keys' => true, 'associative_result' => true, 'values_from_key' => 'name'));
            $this->get('app.utils')->get_key_set($sub_accounts, $sub_account_ids, array('key_to_child_elements' => 'children', 'sort_keys' => true, 'associative_result' => true,
                'values_from_key' => 'name'));
            $this->debug('sub_account_ids[w/o key_to_child_elements, associative_result:: true and values_from_key:: name]:: '.print_r($sub_account_ids, true));

            // $sub_accounts_backup = $sub_accounts;
            // $this->get('app.utils')->convert_fields($sub_accounts, array('modify_reference' => true, 'key_to_child_elements' => 'sub_accounts',
            $this->get('app.utils')->convert_fields($sub_accounts, array('modify_reference' => true, 'key_to_child_elements' => 'children',
                'field_conversion_specs' => array(
                    array('field_name' => 'text', 'function_name' => 'concatenate_values',
                        'arguments' => array(
                            array('name' => 'name', 'email' => 'email'),
                            array('dummy_argument' => true, 'delimiter' => ' ', 'delimiter_specs' => array(
                                    'email' => array('start_delimiter' => '(', 'end_delimiter' => ')',
                                        'prepend_global_delimiter' => true)))
                        )
                    )
                // , array('function_name' => 'copy_array_value', 'arguments' => array('data_array', 'sub_accounts', 'children'), 'forced_literals' => array('sub_accounts'))
                )
            ));

            $this->debug('sub_accounts (in associative form) processed w/ convert_fields:: '.print_r($sub_accounts, true));
            $this->debug('sub_accounts (in associative form) processed w/ convert_fields as JSON:: '.json_encode($sub_accounts));
        }

        /*
          $sub_accounts = $sub_accounts_backup;
          $this->get('app.utils')->convert_fields($sub_accounts, array('modify_reference' => true, 'key_to_child_elements' => 'sub_accounts',
          'field_conversion_specs' => array(
          array('field_name' => 'text', 'function_name' => 'concatenate_values',
          'arguments' => array(
          array('name' => 'name', 'email' => 'email'),
          array('dummy_argument' => true, 'delimiter' => ' ', 'delimiter_specs' => array('email' => array('start_delimiter' => '(', 'end_delimiter' => ')', 'prepend_global_delimiter' => true)))
          )
          ),
          array('function_name' => 'copy_array_value', 'arguments' => array('data_array', 'sub_accounts', 'children', array('copy_by_reference' => false)), 'forced_literals' => array('sub_accounts'))
          )
          ));

          $this->debug('sub_accounts (in associative form) processed w/ convert_fields, using copy_by_reference:: false:: '.print_r($sub_accounts, true));



          $sub_accounts = $sub_accounts_backup;
          $this->get('app.utils')->convert_fields($sub_accounts, array('modify_reference' => true, 'key_to_child_elements' => 'sub_accounts',
          'field_conversion_specs' => array(
          array('field_name' => 'text', 'function_name' => 'concatenate_values',
          'arguments' => array(
          array('name' => 'name', 'email' => 'email'),
          array('dummy_argument' => true, 'delimiter' => ' ', 'delimiter_specs' => array('email' => array('start_delimiter' => '(', 'end_delimiter' => ')', 'prepend_global_delimiter' => true)))
          )
          ),
          array('function_name' => 'move_array_value', 'arguments' => array('data_array', 'sub_accounts', 'children'), 'forced_literals' => array('sub_accounts'))
          )
          ));

          $this->debug('sub_accounts (in associative form) processed w/ convert_fields, using move_array_value:: '.print_r($sub_accounts, true));
         */


        // $manager = Doctrine_Manager::getInstance(); // Doctrine_Manager not found
        // $this->debug('Connection Name:: '.$manager->getConnectionName(Doctrine_Manager::connection()));
        // $this->debug('Connection count:: '.count($manager->getConnections()));
        // if ($this->data['isUserLoggedIn'])
        // {
        /*
          $this->debug("isUserLoggedIn: ".($this->get('ApiUserServices')->tt_global_get('isLogged')?'yes':'no'));
          $this->debug("userInfo:: ".print_r($this->get('ApiUserServices')->tt_global_get('userInfo'), true));

          $this->debug("<br/><br/>data:: ".print_r($this->data, true));
         */

        $this->debug("randomString1:: ".$this->get('app.utils')->randomString(7));
        $this->debug("randomString2:: ".$this->get('app.utils')->randomString(7, array('use_letters' => false)));
        $this->debug("randomString3:: ".$this->get('app.utils')->randomString(7, array('use_letters' => false, 'chars' => array('^', '#', '(', '@'))));

        return $this->render('default/test.twig', $this->data);
        // }

        $coupon_source = $this->get('TTServices')->getCouponSource($this->container->getParameter('SOCIAL_ENTITY_HOTEL'));
        $this->debug("coupon_source:: ".print_r($coupon_source, true));

        /*
          $activeCampaigns = $this->get('TTServices')->activeCampaigns(array('target_entity_type_id' => $this->container->getParameter('SOCIAL_ENTITY_FLIGHT')), false);
          $this->debug($activeCampaigns);
         */

        $couponCode = '88638838'; // '88638838';

        $campaign_info = $this->validUnusedCouponsCampaign($couponCode, $this->container->getParameter('SOCIAL_ENTITY_FLIGHT'));

        $this->debug("Coupon ($couponCode):: valid ? ".($campaign_info !== false ? "yes" : "no"));
        return $this->render('default/test.twig', $this->data);
        $currency        = 'AED';
        $original_amount = '3774.11'; // ~ USD 1027.50
        $amount          = $original_amount;

        if ($campaign_info !== false) {
            $this->debug("Coupon ($couponCode) is valid, found campaign:: ".print_r($campaign_info, true));

            $discountedAmountInfo = $this->applyDiscount($campaign_info['c_discountId'], $campaign_info['currency_code'], $currency, $amount);
            $amount               = $discountedAmountInfo['amount'];

            $this->debug("original_amount:: $currency $original_amount ==&gt; $amount, discountedAmountInfo:: ".print_r($discountedAmountInfo, true));

            $discount_details = $this->get('TTServices')->getDiscountDetails($campaign_info['c_discountId']);
            $this->debug("discount_details[".$campaign_info['c_discountId']."]:: ".print_r($discount_details, true));
            $currency         = 'USD';
            $this->debug("discount_displayable_info:: ".print_r($this->getDiscountDisplayableInfo($campaign_info['c_discountId'], $campaign_info['currency_code'], $currency), true));
        }

        $activeCampaigns = $this->get('TTServices')->activeCampaigns(array('source_entity_type_id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL')), false);
        $this->debug("activeCampaigns[source_entity_type_id:: ".$this->container->getParameter('SOCIAL_ENTITY_HOTEL')."]:: ".print_r($activeCampaigns, true));

        $marketing_labels = $this->get('TTServices')->activeCampaignMarketingLabels(array('source_entity_type_id' => $this->container->getParameter('SOCIAL_ENTITY_HOTEL')));
        if ($marketing_labels) $this->debug("marketing_labels:: ".print_r($marketing_labels, true));
        else $this->debug("No marketing_labels found for source_entity_type_id:: ".$this->container->getParameter('SOCIAL_ENTITY_HOTEL'));

        $this->debug("All active campaigns:: ".print_r($this->get('TTServices')->activeCampaigns(null, false), true));

        return $this->render('default/test.twig', $this->data);
    }

    public function photos360PanoAction($type, $entityName, $countryCode, $entityId, $catId, $divId, $seotitle, $seodescription, $seokeywords)
    {

        $this->data['type']            = $type;
        $this->data['name']            = $entityName;
        $pageTitle                     = str_replace('+', ' ', $entityName);
        $pageTitle                     = str_replace('-', ' ', $pageTitle);
        $pageTitle                     = str_replace('_', ' ', $pageTitle);
        $this->data['pageTitle']       = $pageTitle;
        $this->data['country']         = $countryCode;
        $this->data['id']              = $entityId;
        $this->data['cat_id']          = $catId;
        $this->data['div_id']          = $divId;
        $this->data['sub_division_id'] = "";

        return $this->render('media_360/photos-360-pano.twig', $this->data);
    }

    public function video360Action($videoName = 'eiffel-tower', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['videoName'] = $videoName.".xml";
        return $this->render('media_360/video-360.twig', $this->data);
    }

    public function virtualTourAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_virtual_tour'), true, true);

        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal($this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_360_PHOTOS_VIRTUAL_TOUR'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData($this->data['LanguageGet'], $mainEntityType_array);
        if ($this->data['aliasseo'] == '') {
            $action_text_display    = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display          = $this->translator->trans(/** @Ignore */$seodescription, array(), 'seo');
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        return $this->render('default/virtual-tour.twig', $this->data);
    }

    public function liveAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_live'), true, true);

        $mainEntityType_array          = $this->get('TTServices')->getMainEntityTypeGlobal($this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_LIVE'));
        $this->data['mainEntityArray'] = $this->get('TTServices')->getMainEntityTypeGlobalData($this->data['LanguageGet'], $mainEntityType_array);

        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        return $this->render('default/live.twig', $this->data);
    }

    public function liveRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_live', array(), 301);
    }

    /**
      Overrides the parent class's render function
     * */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if (isset($parameters['seotitle']) && $parameters['seotitle']) {
            $parameters['seotitle'] = strtolower($parameters['seotitle']);
            $parameters['seotitle'] = ucwords($this->get('app.utils')->htmlEntityDecode($parameters['seotitle']));
        }

        if (isset($parameters['seodescription']) && $parameters['seodescription']) {
            $parameters['seodescription'] = strtolower($parameters['seodescription']);
            $parameters['seodescription'] = str_replace('tourist tube', 'Tourist Tube', $parameters['seodescription']);
            $parameters['seodescription'] = str_replace('touristtube', 'TouristTube', $parameters['seodescription']);
            $parameters['seodescription'] = preg_replace_callback('/([.!?])\s*(\w)/', function ($matches) {
                return strtoupper($matches[1].' '.$matches[2]);
            }, ucfirst($parameters['seodescription']));
        }

        if (isset($parameters['seodescription']) && (!isset($parameters['fbdesc']) || (isset($parameters['fbdesc']) && !$parameters['fbdesc']))) {
            $parameters['fbdesc'] = $parameters['seodescription'];
        }

        return parent::render($view, $parameters, $response);
    }

    public function setHreflangLinks($url, $ignore_lang = false, $ignore_server = false, $page_type = '')
    {
        $uricurserver                = '';
        if (!$ignore_server && $ignore_lang) $uricurserver                = $this->get('TTRouteUtils')->currentServerURL();
        if (!$ignore_lang) $url                         = $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], $url, $page_type);
        $this->data['canonicallink'] = $uricurserver.$url;
        $ur_arraydata                = '';
        $ur_array                    = $this->get('TTRouteUtils')->UriCanonicalPageURLForLG($this->data['canonicallink']);
        $langarray                   = $this->data['lang_arraydata'];
        foreach ($langarray as $lang_items) {
            $langitem             = array();
            $lang_key             = $lang_items['lang_key'];
            $lang_val             = $lang_items['lang_val'];
            $lang_name            = $lang_items['name'];
            if ($lang_key != 'en') $langUrl              = $ur_array[0].$ur_array[1].'/'.$lang_val.'/'.$ur_array[2];
            else $langUrl              = $ur_array[0].$ur_array[1].'/'.$ur_array[2];
            $ur_arraydata         .= '<link rel="alternate" hreflang="'.$lang_key.'" href="'.$langUrl.'"/>';
            $langitem['selected'] = 0;
            if ($this->data['LanguageGet'] == $lang_val || $this->data['LanguageGet'] == $lang_key) {
                $langitem['selected'] = 1;
            }
            $langitem['lang_key'] = $lang_key;
            $langitem['lang_val'] = $lang_val;
            $langitem['link']     = $langUrl;
            $langitem['name']     = $lang_name;
            $lang_arraydata[]     = $langitem;
        }
        $this->data['ur_arraydata']   = $ur_arraydata;
        $this->data['lang_arraydata'] = $lang_arraydata;
        $this->data['subdomain_link'] = $ur_array[0];
    }

    public function logoutAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $pathcookie       = '/';
        $configCookiePath = $this->container->getParameter('CONFIG_COOKIE_PATH');
        setcookie("referer_page", '', 0, $pathcookie, $configCookiePath);

        $this->userLogout();
        //        header('Location: ' . $request->server->get('HTTP_REFERER', '') );
        return new RedirectResponse($request->server->get('HTTP_REFERER', ''));
        return new Response('');
    }

    public function registerAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['isUserLoggedIn'] == 1) {
            return $this->redirectToLangRoute('_welcome', array(), 301);
        }
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $this->setHreflangLinks("/register");
        $this->data['facebook_app_id']                = $this->container->getParameter('facebook_app_id');
        $this->data['facebook_default_graph_version'] = $this->container->getParameter('facebook_default_graph_version');
        return $this->render('default/register.twig', $this->data);
    }

    public function registerSuccessAction($channel = '', $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->data['showfooter'] = 0;
        $this->data['types']      = $channel;

        return $this->render('default/register.success.twig', $this->data);
    }

    public function logInDefaultAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        if ($this->data['isUserLoggedIn'] == 1) {
            //            header('Location:' . '/' );

            return $this->redirectToLangRoute('_welcome', array(), 301);
        }
        $this->setHreflangLinks("/login");
        $this->data['facebook_app_id']                = $this->container->getParameter('facebook_app_id');
        $this->data['facebook_default_graph_version'] = $this->container->getParameter('facebook_default_graph_version');
        return $this->render('default/login.twig', $this->data);
    }

    public function oldindexAction()
    {
        $request     = $this->get('request');
        $uricurpage  = $this->get('TTRouteUtils')->UriCurrentPageURL();
        $url         = explode('/', $uricurpage);
        $last_record = count($url) - 1;
        $lang        = $url[$last_record];
        $request->setLocale($lang);
        return $this->redirectToLangRoute('_welcome', array(), 301);
    }

    public function popupOffersAction($seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
        }
        return $this->render('default/popup-offers.twig', $this->data);
    }

    public function indexAction($seotitle, $seodescription, $seokeywords)
    {
        $this->data['hotelblocksearchIndex']  = 1;
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['dealblocksearchIndex']   = 1;
        $this->data['isindexpage']            = 1;
        $this->data['datapagename']           = 'index';
        $mainEntityType_array                 = $this->get('TTServices')->getMainEntityTypeGlobal($this->data['LanguageGet'], $this->container->getParameter('PAGE_TYPE_HOME'));
        $this->data['mainEntityArray']        = $this->get('TTServices')->getMainEntityTypeGlobalData($this->data['LanguageGet'], $mainEntityType_array);

        $this->setHreflangLinks('');

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
        $this->data['input'] = array();
        $thingstodoListAll   = array();
        
        $options = array( 'show_main' => 1, 'lang' => $this->data['LanguageGet'], 'from_mobile' => 0 );
        $thingstodo_array = $this->get('ApiDiscoverServices')->thingsTodoRegionQuery( $options );
        $thingstodoListAll = array_merge( $thingstodoListAll, $thingstodo_array );
        
        $options = array( 'show_main' => 1, 'lang' => $this->data['LanguageGet'], 'from_mobile' => 0 );
	$thingstodo_array = $this->get('ApiDiscoverServices')->thingsTodoCountryQuery( $options );
        $thingstodoListAll = array_merge( $thingstodoListAll, $thingstodo_array );
        
        $options    = array( 'lang' => $this->data['LanguageGet'], 'city_id' => 0, 'from_mobile' => 0 );
        $thingstodo_array = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );
        $thingstodoListAll = array_merge( $thingstodoListAll, $thingstodo_array );
        
        $whereCities = $this->get('TTServices')->cityHomeWhereIs($this->data['LanguageGet']);
        $thingstodoListAll = array_merge( $thingstodoListAll, $whereCities );
        
        $this->data['thingstodoListAll'] = $thingstodoListAll;
        return $this->render('default/indexFinal.twig', $this->data);
    }

    public function oldUrlAction($target)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /$target");
        exit();
    }

    public function discoverAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_discover'), true, true);
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $discoverItem = $this->get("HRSServices")->getHotelSelectedCityId();
        foreach ($discoverItem as $discover) {
            $adiscoverInfo['name']    = $this->translator->trans('Discover');
            $adiscoverInfo['city']    = $this->get('app.utils')->htmlEntityDecode($discover['hc_name']);
            $adiscoverInfo['namealt'] = $this->translator->trans('Discover').' '.$this->get('app.utils')->cleanTitleDataAlt($discover['hc_name']);
            $cityid                   = $discover['w_id'];
            $statecode                = '';
            $countrycode              = $discover['hc_countryCode'];
            if ($discover['w_countryCode'] != '') {
                $countrycode = $discover['w_countryCode'];
            }
            $statecode             = $discover['w_stateCode'];
            $adiscoverInfo['link'] = $this->get('TTRouteUtils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $discover['hc_name'], $cityid, $statecode, $countrycode);
            $sourcepath            = 'media/hotels/hotelbooking/hotel-main-banner/';
            $adiscoverInfo['img']  = $this->get("TTMediaUtils")->createItemThumbs($discover['hc_image'], $sourcepath, 0, 0, 282, 160, 'discovers282160', $sourcepath, $sourcepath, 80);
            $discoverInfo[]        = $adiscoverInfo;
        }
        $this->data['discoverInfo'] = $discoverInfo;
        return $this->render('default/discover.twig', $this->data);
    }

    public function discoverDetailedAction($srch1, $srch2, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'discover_detailed';
        $res                        = explode('_', $srch2);
        $cityid                     = 0;
        $longitude                  = 0;
        $latitude                   = 0;
        $countrycode                = $statecode                  = $statename                  = $discovername               = $realname                   = '';
        $tmp_data_array             = array();
        if (sizeof($res) == 3) {
            $cityid      = intval($res[0]);
            $countrycode = $res[1];
            $statecode   = "".$res[2];
        } else if (sizeof($res) == 2) {
            $countrycode = $res[0];
            $statecode   = "".$res[1];
        } else if (sizeof($res) == 1) {
            $countrycode = $res[0];
        } else {
            return $this->pageNotFoundAction();
        }
        if ($statecode == "0") $statecode = "";
        if ($cityid != 0) {
            $cityInfo = $this->get('CitiesServices')->worldcitiespopInfo($cityid);
            if ($cityInfo) {
                $countrycode  = $cityInfo[0]->getCountryCode();
                $statecode    = "".$cityInfo[0]->getStateCode();
                $realname     = $this->get('app.utils')->htmlEntityDecode($cityInfo[0]->getName());
                $statename    = $discovername = $this->get('app.utils')->htmlEntityDecode($cityInfo[0]->getName()).' '.$cityInfo[1]->getIso3();
                $longitude    = $cityInfo[0]->getLongitude();
                $latitude     = $cityInfo[0]->getLatitude();
                if (!isset($tmp_data_array)) {
                    $tmp_data_array = array('city' => array('name' => $realname));
                }
            } else {
                $cityid        = 0;
                $statecode     = '';
                $country_array = $this->get('CmsCountriesServices')->countryGetInfo($countrycode);
                if ($country_array) {
                    $realname     = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
                    $discovername = $this->get('app.utils')->htmlEntityDecode($country_array->getName()).' '.$country_array->getIso3();
                    $longitude    = $country_array->getLongitude();
                    $latitude     = $country_array->getLatitude();
                    if (!isset($tmp_data_array)) {
                        $tmp_data_array = array('country' => array('name' => $realname));
                    }
                }
            }
        } else if ($countrycode && $statecode) {
            $state_array = $this->get('CitiesServices')->worldStateInfo($countrycode, $statecode);
            if ($state_array && sizeof($state_array)) {
                $country_array = $state_array[1];
                $realname      = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                $statename     = $discovername  = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName()).' '.$country_array->getIso3();
                if (!isset($tmp_data_array)) {
                    $tmp_data_array = array('state' => array('name' => $realname));
                }
            } else {
                $country_array = $this->get('CmsCountriesServices')->countryGetInfo($countrycode);
                $statecode     = '';
                if ($country_array) {
                    $realname     = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
                    $discovername = $this->get('app.utils')->htmlEntityDecode($country_array->getName()).' '.$country_array->getIso3();
                    if (!isset($tmp_data_array)) {
                        $tmp_data_array = array('country' => array('name' => $realname));
                    }
                }
            }
            if ($countrycode == 'US' && $statecode == 'CA') {
                $longitude = -120.202137;
                $latitude  = 36.509220;
            } else {
                if ($country_array) {
                    $longitude = $country_array->getLongitude();
                    $latitude  = $country_array->getLatitude();
                }
            }
        } else {
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($countrycode);
            if ($country_array) {
                $realname     = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
                $discovername = $this->get('app.utils')->htmlEntityDecode($country_array->getName()).' '.$country_array->getIso3();
                $longitude    = $country_array->getLongitude();
                $latitude     = $country_array->getLatitude();
                if (!isset($tmp_data_array)) {
                    $tmp_data_array = array('country' => array('name' => $realname));
                }
            }
        }
        $this->data['url_current'] = $this->get('TTRouteUtils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $realname, $cityid, $statecode, $countrycode);
        $this->setHreflangLinks($this->data['url_current'], true, true);


        if ($statecode === 0) $statecode = "";
        if ($this->data['aliasseo'] == '') {
            $action_array                 = array();
            $action_array[]               = $discovername;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $limit         = 24;
        $cityidSH      = $cityid;
        $countrycodeSH = $countrycode;
        $statecodeSH   = $statecode;

        if ($cityidSH == 0) {
            $cityidSH = NULL;
            if ($statecodeSH == '') {
                $statecodeSH = NULL;
            }
            if (!$country_array || $countrycodeSH == '') {
                return $this->pageNotFoundAction();
            }
        } else {
            $countrycodeSH = NULL;
            $statecodeSH   = NULL;
        }
        $srch_options = array(
            'limit' => $limit,
            'page' => 0,
            'is_public' => 2,
            'city_id' => $cityidSH,
            'country' => $countrycodeSH,
            'statename' => $statename,
            'orderby' => 'nbViews',
            'type' => 'a',
            'order' => 'd',
            'lang' => $this->data['LanguageGet']
        );
        $medialist    = $this->get('PhotosVideosServices')->mediaSearch($srch_options);
        $mediaarray   = array();
        $i            = 0;
        if (sizeof($medialist) < 8) $medialist    = array();
        foreach ($medialist as $items) {
            $iarray = array();
            if (($i % 12) == 4) {
                $realpath     = $items['v_relativepath'];
                $relativepath = str_replace('/', '', $realpath);
                $fullPath     = $items['v_fullpath'];
                $itemsname    = $items['v_name'];

                if ($items['v_imageVideo'] == "v") {
                    $iarray['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($items, 'discoverLong1');
                } else {
                    $iarray['img'] = $this->get("TTMediaUtils")->createItemThumbs($itemsname, $items['v_fullpath'], 0, 0, 284, 325, 'discoverLong1', $items['v_fullpath'], $fullPath);
                }
            } else if (($i % 12) == 5) {
                $realpath     = $items['v_relativepath'];
                $relativepath = str_replace('/', '', $realpath);
                $fullPath     = $items['v_fullpath'];
                $itemsname    = $items['v_name'];

                if ($items['v_imageVideo'] == "v") {
                    $iarray['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($items, 'discoverLong2');
                } else {
                    $iarray['img'] = $this->get("TTMediaUtils")->createItemThumbs($itemsname, $items['v_fullpath'], 0, 0, 578, 325, 'discoverLong2', $items['v_fullpath'], $fullPath);
                }
            } else {
                $iarray['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($items, 'small');
            }
            $iarray['imagetype'] = $items['v_imageVideo'];

            $titles            = $items['v_title'];
            if ($items['mlv_title']) $titles            = $items['mlv_title'];
            $iarray['name']    = $this->get('app.utils')->htmlEntityDecode($titles);
            $iarray['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($titles);

            $iarray['link'] = $this->get("TTMediaUtils")->returnMediaUriHashedFromArray($items, $this->data['LanguageGet']);
            $mediaarray[]   = $iarray;
            $i++;
        }

        $reststate = $statename;
        if ($cityid > 0) {
            $reststate = '';
        }
        $vallimit  = 8;
        $request   = Request::createFromGlobals();
        $routepath = $this->getRoutePath($request);

        $BagItmArray = array();
        $BagItmArray = $this->get('UserServices')->returnBagItemData($this->data['USERID'], 0, $this->data['LanguageGet']);
        
        $srch_option = array(
            'url_source' => 'discoverDetailedAction -  - getHotelSearchData - URL: '.$routepath,
            'cityId' => $cityid, 
            'countryCode' => $countrycode, 
            'sortBy' => 'stars',
            'page' => 0, 
            'limit' => $vallimit, 
            'oldQuery' => 1,
            'lang' =>$this->data['LanguageGet'],
            'sortbyOrder' => 'desc'
        );
        $hotelss     = $this->get("ElasticServices")->getHotelSearchData($srch_option);
        
        $hotels_array               = $hotelss[0];
        $hotelsscount               = $hotelss[1];
        $this->data['hotelsInLink'] = '';

        $url_source = 'discoverDetailedAction - getChannelsListDiscoverData - URL: '.$routepath;
        $options = array( 
            'limit' => 4, 
            'page' => 0, 
            'city_id' => $cityid, 
            'country' => $countrycode, 
            'state_name' => $reststate, 
            'lang' => $this->data['LanguageGet'], 
            'url_source' => $url_source
        );        
        list( $channels_array, $channelsscount ) = $this->get('ChannelServices')->getChannelsListDiscoverData( $options );

        $this->data['moreChannelslink'] = $this->get('TTRouteUtils')->returnChannelsSearchLink($this->data['LanguageGet'], '', $cityid, '', $countrycode);

        $todoLink     = $todoLinkName = '';
        $pois_array  = array();
        if ($cityid > 0) {
            $Results = $this->container->get('ThingsToDoServices')->getPoiTopList($countrycode, $reststate, $cityid, 6, 'rand', $this->data['LanguageGet']);
            $todoLink     = $Results['todoLink'];
            $todoLinkName = $Results['todoLinkName'];
            $pois_array   = $Results['pois_array'];
        } else if ($countrycode != '') {

            $options    = array(
                'show_main' => NULL,
                'limit' => 6,
                'lang' => $this->data['LanguageGet'],
                'country_code' => $countrycode,
                'img_width' => 284,
                'img_height' => 162,
                'desc_length' => 250,
                'from_mobile' => 0
            );
            $pois_array = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );
            if( sizeof( $pois_array ) > 0 )
            {
                $todoLink = $pois_array[0]['parent_alias'];
                $todoLinkName = $pois_array[0]['parent_name'];
            }
        }
        $this->data['todo_array']   = $pois_array;
        $this->data['todoLink']     = $todoLink;
        $this->data['todoLinkName'] = $todoLinkName;

        $moreDealslist = array();
        $moreDealslink = '';
        if ($cityid > 0 && $this->show_deals_block == 1) {
            $moreDealslink                       = $this->get('TTRouteUtils')->returnDealsSearchLink($this->data['LanguageGet'], $realname);
            $dealEnhancedSearchByDealNameEncoded = $this->get('DealServices')->getDealSearchByCityId($cityid, $vallimit);
            $dealEnhancedSearchByDealNameDecoded = json_decode($dealEnhancedSearchByDealNameEncoded, true);
            $dealEnhancedSearchByDealNameList    = $dealEnhancedSearchByDealNameDecoded['data'];
            if ($dealEnhancedSearchByDealNameList) {
                foreach ($dealEnhancedSearchByDealNameList as $itemDeal) {
                    $itemDealslist              = array();
                    $itemDealslist['link']      = $itemDeal['link'];
                    $itemDealslist['name']      = $this->get('app.utils')->htmlEntityDecode($itemDeal['dealName']);
                    $itemDealslist['namealt']   = $this->get('app.utils')->cleanTitleDataAlt($itemDeal['dealName']);
                    $itemDealslist['img']       = $itemDeal['imagePath'];
                    $itemDealslist['price']     = number_format($itemDeal['price'], 2, '.', ',');
                    $itemDealslist['dataprice'] = $itemDeal['price'];
                    $moreDealslist[]            = $itemDealslist;
                }
            }
        }
        $this->data['moreDealslink'] = $moreDealslink;
        $this->data['moreDealslist'] = $moreDealslist;

        $descDiscover      = $descDiscoverTitle = $discoverLink      = '';
        if ($cityid > 0) {
            $options_arr    = array(
                'show_main' => 0,
                'limit' => 40,
                'lang' => $this->data['LanguageGet'],
                'city_id' => $cityid
            );
            $thingstodoList = $this->container->get('ThingsToDoServices')->getThingstodoList($options_arr);
            foreach ($thingstodoList as $thingstodoInfo) {
                if (isset($thingstodoInfo['t_descDiscover']) && $thingstodoInfo['t_descDiscover']) {
                    if ($thingstodoInfo['ml_descDiscover'] != '') $descDiscover      = $thingstodoInfo['ml_descDiscover'];
                    else $descDiscover      = $thingstodoInfo['t_descDiscover'];
                    $descDiscoverTitle = $this->translator->trans('Hotels in').' '.$realname;
                    $discoverLink      = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $realname, $cityid, '', '', 1);
                    break;
                }
            }
        }
        $this->data['descDiscover']      = $descDiscover;
        $this->data['descDiscoverTitle'] = $descDiscoverTitle;
        $this->data['discoverLink']      = $discoverLink;

        $this->data['cityid']                 = $cityid;
        $this->data['countrycode']            = $countrycode;
        $this->data['BagItmArray']            = $BagItmArray;
        $this->data['channels_array']         = $channels_array;
        $this->data['hotels_array']           = $hotels_array;
        $this->data['mediaarray']             = $mediaarray;
        $this->data['discovername']           = $realname;
        $this->data['discovernamealt']        = $this->get('app.utils')->cleanTitleDataAlt($realname);
        $this->data['longitude']              = $longitude;
        $this->data['latitude']               = $latitude;
        $this->data['SOCIAL_ENTITY_AIRPORT']  = $this->container->getParameter('SOCIAL_ENTITY_AIRPORT');
        $this->data['SOCIAL_ENTITY_HOTEL']    = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');
        $this->data['SOCIAL_ENTITY_LANDMARK'] = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK');
        $this->data['SOCIAL_ENTITY_CHANNEL']  = $this->container->getParameter('SOCIAL_ENTITY_CHANNEL');
        $this->data['SOCIAL_ENTITY_EVENTS']   = $this->container->getParameter('SOCIAL_ENTITY_EVENTS');

        return $this->render('default/discover_detailed.twig', $this->data);
    }

    public function nearbyGeneralRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_nearby_general', array(), 301);
    }

    public function nearbyGeneralAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_nearby_general'), true, true);

        $nearByAll  = array();
        $nearByList = $this->get('ReviewsServices')->getDiscoverPoiNearByList(52);

        foreach ($nearByList as $item) {
            $incl = explode(',', $item['p_nearbyIncludes']);
            foreach ($incl as $itemincludes) {
                $anearByAll = array();
                $poi_id     = $item['p_id'];
                if ($itemincludes == 'h') {
                    $link = $this->get('TTRouteUtils')->returnHotelsNearByLink($this->data['LanguageGet'], $item['p_name'], $poi_id, 1);
                    $name = 'hotels near by '.$item['p_name'];
                } else {
                    continue;
                }

                $anearByAll['name'] = $name;
                $anearByAll['link'] = $link;
                $nearByAll[]        = $anearByAll;
            }
        }
        $this->data['nearbyAll'] = $nearByAll;
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        return $this->render('default/nearby.twig', $this->data);
    }

    public function nearbyGeneralHomeAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_nearby_home'), true, true);

        $nearByAll  = array();
        $nearByList = $this->get('ReviewsServices')->getDiscoverPoiNearByList(36, 'rand');

        foreach ($nearByList as $item) {
            $incl = explode(',', $item['p_nearbyIncludes']);
            foreach ($incl as $itemincludes) {
                $anearByAll = array();
                $poi_id     = $item['p_id'];
                if ($itemincludes == 'h') {
                    $link = $this->get('TTRouteUtils')->returnHotelsNearByLink($this->data['LanguageGet'], $item['p_name'], $poi_id, 1);
                    $name = 'hotels near by '.$item['p_name'];
                } else {
                    continue;
                }

                $anearByAll['name'] = $name;
                $anearByAll['link'] = $link;
                $nearByAll[]        = $anearByAll;
            }
        }
        $this->data['nearbyAll']      = $nearByAll;
        $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));

        return $this->render('default/nearby.twig', $this->data);
    }

    public function whereIsHomeAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_where_is_home'), true, true);

        $whereisAll = array();
        $whereisstr = $this->translator->trans('Where is').' ';

        $whereCities = array(1668543, 942401, 2228199, 2239601, 198566, 1676414, 1806934, 858320, 1240738, 1293855, 1843196, 2223410, 920073, 2106650);
        foreach ($whereCities as $whereCity) {
            $cityInfo            = $this->get('CitiesServices')->worldcitiespopInfo(intval($whereCity));
            $awhereisAll['name'] = $whereisstr.$this->get('app.utils')->htmlEntityDecode($cityInfo[0]->getName());
            $awhereisAll['link'] = $this->get('TTRouteUtils')->returnWhereIsLink($this->data['LanguageGet'], $cityInfo[0]->getName(), $cityInfo[0]->getId(), '', '');
            $whereisAll[]        = $awhereisAll;
        }

        $whereCities = array('DE', 'TR', 'IT', 'TH', 'ZW', 'PA', 'JO', 'BY', 'ST', 'NL', 'VN', 'ZA', 'NZ', 'NA', 'SG', 'BL', 'LI', 'ZM');
        foreach ($whereCities as $whereCo) {
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($whereCo);
            if ($country_array) {
                $country_name        = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
                $awhereisAll['name'] = $whereisstr.$country_name;
                $awhereisAll['link'] = $this->get('TTRouteUtils')->returnWhereIsLink($this->data['LanguageGet'], $country_array->getName(), 0, '', $whereCo);
                $whereisAll[]        = $awhereisAll;
            }
        }
        $this->data['whereisAll'] = $whereisAll;

        $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));

        return $this->render('default/where-is.twig', $this->data);
    }

    public function whereIsGeneralAction($seotitle, $seodescription, $seokeywords)
    {
        $this->setHreflangLinks($this->generateLangRoute('_where_is_general'), true, true);

        $whereisAll  = array();
        $whereisstr  = $this->translator->trans('Where is').' ';
        $whereCities = array(1818316, 1829266, 1384846, 1813535, 1774854, 1257817,
            1294582, 1584133, 1892508, 7869, 914821, 2449862, 1767969, 1738889, 1653366,
            1323805, 1740637, 131952, 2331671, 1828071, 2227462, 1582289, 2100985,
            1740558, 774729, 1917563, 846162, 763054, 2224674);
        foreach ($whereCities as $whereCity) {
            $cityInfo            = $this->get('CitiesServices')->worldcitiespopInfo(intval($whereCity));
            $awhereisAll['name'] = $whereisstr.$this->get('app.utils')->htmlEntityDecode($cityInfo[0]->getName());
            $awhereisAll['link'] = $this->get('TTRouteUtils')->returnWhereIsLink($this->data['LanguageGet'], $cityInfo[0]->getName(), $cityInfo[0]->getId(), '', '');
            $whereisAll[]        = $awhereisAll;
        }
        $whereCities = array('MA', 'ID', 'LK', 'BS', 'BB', 'BZ', 'CA', 'FR', 'GB',
            'CU', 'AE', 'AR', 'AU', 'BE', 'BR', 'BW', 'CI', 'CL', 'CH', 'CY', 'DK',
            'GH', 'GR', 'HR', 'IE', 'MX', 'NO', 'SE', 'IN', 'FJ', 'FI', 'ES', 'BO',
            'CN', 'GE', 'PT', 'HK', 'US');
        foreach ($whereCities as $whereCo) {
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($whereCo);
            if ($country_array) {
                $country_name        = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
                $awhereisAll['name'] = $whereisstr.$country_name;
                $awhereisAll['link'] = $this->get('TTRouteUtils')->returnWhereIsLink($this->data['LanguageGet'], $country_array->getName(), 0, '', $whereCo);
                $whereisAll[]        = $awhereisAll;
            }
        }
        $this->data['whereisAll'] = $whereisAll;
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        return $this->render('default/where-is.twig', $this->data);
    }

    public function whereIsGeneralRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_where_is_general', array(), 301);
    }

    public function whereIsRedirectAction($srch, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_where_is', array('srch' => $srch), 301);
    }

    public function whereIsAction($srch, $seotitle, $seodescription, $seokeywords)
    {
        $request            = Request::createFromGlobals();
        $routepath          = $this->getRoutePath($request);
        $srch               = str_replace('/', '+', $srch);
        $search_str         = urldecode($srch);
        $search_str         = explode('-', $search_str);
        $last_record        = count($search_str) - 1;
        $rest1              = explode('_', $search_str[$last_record]);
        $c_id               = 0;
        $co_id              = "";
        $s_id               = "";
        $statename          = "";
        $realnameseo        = "";
        $realnameseoTitle   = "";
        $structurePlaceType = "";
        if (sizeof($rest1) < 2) {
            return $this->pageNotFoundAction();
        }
        if (strtoupper($rest1[0]) != "C" && strtoupper($rest1[0]) != "S" && strtoupper($rest1[0]) != "CO") {
            if ((sizeof($search_str)) <= 2) {
                return $this->pageNotFoundAction();
            }
            $c_id = intval($search_str[2]);
            $type = 1;
        }
        $parent_name = '';
        $name        = '';
        switch (strtoupper($rest1[0])) {
            case "CO":
                $co_id              = $rest1[1];
                $type               = 3;
                $structurePlaceType = "Country";
                $c_id               = 0;
                $s_id               = "";
                list( $name, $realname, $realnameseo, $realnameseoTitle, $where_latitude, $where_longitude, $descriptions, $medialink ) = $this->get('CitiesServices')->getCountryInfoData($co_id, $this->data['LanguageGet']);
                if (!$name) return $this->pageNotFoundAction();
                break;
            case "S":
                $s_id               = $rest1[1];
                $co_id              = $rest1[2];
                $type               = 2;
                $structurePlaceType = "State";
                $c_id               = 0;
                list( $name, $realname, $realnameseo, $realnameseoTitle, $where_latitude, $where_longitude, $descriptions, $medialink, $parent_name ) = $this->get('CitiesServices')->getStatesInfoData($co_id, $s_id, $this->data['LanguageGet']);
                if (!$name) return $this->pageNotFoundAction();
                break;
            default:
                $c_id               = intval($rest1[1]);
                $type               = 1;
                $structurePlaceType = "City";
                list( $name, $realname, $realnameseo, $realnameseoTitle, $where_latitude, $where_longitude, $descriptions, $medialink, $city_info_name, $co_id, $s_id, $parent_name ) = $this->get('CitiesServices')->getCityInfoData($c_id, $this->data['LanguageGet']);
                if (!$name) return $this->pageNotFoundAction();
                break;
        }
        $this->data['whereistype'] = $type;
        $this->data['description'] = $descriptions;

        $this->setHreflangLinks($this->get('TTRouteUtils')->returnWhereIsLink($this->data['LanguageGet'], $realname, $c_id, $s_id, $co_id), true, true);

        $realnameseo      = str_replace('+', ' ', $realnameseo);
        $realnameseo1     = str_replace('+', ' ', $realname);
        $realnameseoTitle = str_replace('+', ' ', $realnameseoTitle);
        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_array[]         = $realnameseoTitle;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_array[]               = $realnameseo;
            $action_array[]               = $realnameseo1;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $realnameseo;
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $this->data['parent_name']     = $parent_name;
        $this->data['realname']        = $realname;
        $this->data['name']            = $name;
        $this->data['where_latitude']  = $where_latitude;
        $this->data['where_longitude'] = $where_longitude;

        
        $srch_option = array(
            'url_source' => 'whereIsAction -  - getHotelSearchData - URL: '.$routepath,
            'cityId' => $c_id, 
            'countryCode' => $co_id, 
            'sortBy' => 'stars',
            'page' => 0, 
            'limit' => 8, 
            'oldQuery' => 1,
            'lang' =>$this->data['LanguageGet'],
            'sortbyOrder' => 'desc'
        );
        $hotelss     = $this->get("ElasticServices")->getHotelSearchData($srch_option);
        
        $this->data['hotels_array'] = $hotelss[0];
        $this->data['hotelscount']  = $hotelss[1];
        if (!isset($tmp_data_array)) {
            $tmp_data_array = array('city' => array('name' => $search_str[1]));
            if (isset($city_info_name)) {
                $tmp_data_array['city']['name'] = $city_info_name;
            }
        }

        $this->data['hotelsInLink'] = ''; //$this->returnHotelSearchLink($c_id, $co_id, $s_id, $tmp_data_array);

        $todoLink     = $todoLinkName = '';
        $pois_array  = array();
        if ($c_id > 0) {
            $Results = $this->container->get('ThingsToDoServices')->getPoiTopList($co_id, $s_id, $c_id, 6, '', $this->data['LanguageGet']);
            $todoLink     = $Results['todoLink'];
            $todoLinkName = $Results['todoLinkName'];
            $pois_array   = $Results['pois_array'];
        } else if ($co_id != '') {

            $options_arr    = array(
                'show_main' => NULL,
                'limit' => 6,
                'lang' => $this->data['LanguageGet'],
                'country_code' => $co_id
            );
            
            $options    = array(
                'show_main' => NULL,
                'limit' => 6,
                'lang' => $this->data['LanguageGet'],
                'country_code' => $co_id,
                'img_width' => 284,
                'img_height' => 162,
                'desc_length' => 250,
                'from_mobile' => 0
            );
            $pois_array = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );
            if( sizeof( $pois_array ) > 0 )
            {
                $todoLink = $pois_array[0]['parent_alias'];
                $todoLinkName = $pois_array[0]['parent_name'];
            }
        }
        $this->data['todo_array']  = $pois_array;
        $this->data['todoLink']     = $todoLink;
        $this->data['todoLinkName'] = $todoLinkName;

        $moreDealslist               = array();
        $this->data['moreDealslink'] = '';
        $this->data['toursNumber']   = 0;
        if ($this->show_deals_block == 1) {
            $this->data['moreDealslink'] = $this->get('TTRouteUtils')->returnDealsSearchLink($this->data['LanguageGet'], $realname);
            $this->data['toursNumber']   = $this->get('DealServices')->getDealTypeToursNumber(array('cityName' => $realname), 'all');
            if ($c_id > 0) {
                $dealEnhancedSearchByDealNameEncoded = $this->get('DealServices')->getDealSearchByCityId($c_id, 8);
                $dealEnhancedSearchByDealNameDecoded = json_decode($dealEnhancedSearchByDealNameEncoded, true);
                $dealEnhancedSearchByDealNameList    = $dealEnhancedSearchByDealNameDecoded['data'];
                if ($dealEnhancedSearchByDealNameList) {
                    foreach ($dealEnhancedSearchByDealNameList as $itemDeal) {
                        $itemDealslist              = array();
                        $itemDealslist['link']      = $itemDeal['link'];
                        $itemDealslist['name']      = $this->get('app.utils')->htmlEntityDecode($itemDeal['dealName']);
                        $itemDealslist['namealt']   = $this->get('app.utils')->cleanTitleDataAlt($itemDeal['dealName']);
                        $itemDealslist['img']       = $itemDeal['imagePath'];
                        $itemDealslist['price']     = number_format($itemDeal['price'], 2, '.', ',');
                        $itemDealslist['dataprice'] = $itemDeal['price'];
                        $moreDealslist[]            = $itemDealslist;
                    }
                }
            }
        }
        $this->data['moreDealslist'] = $moreDealslist;

        $videos       = $this->get('PhotosVideosServices')->getCityMediaList($co_id, $s_id, $c_id, 'i', 8);
        $videos_array = array();
        foreach ($videos as $v_item) {
            $varr             = array();
            $varr['image']    = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($v_item, 'small');
            $varr['link']     = $this->get("TTMediaUtils")->returnMediaUriHashedFromArray($v_item, $this->data['LanguageGet']);
            $varr['title']    = $this->get('app.utils')->htmlEntityDecode($v_item['v_title']);
            $varr['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($v_item['v_title']);
            $videos_array[]   = $varr;
        }
        $this->data['videos_array'] = $videos_array;

        $url_source   = 'whereIsAction - getPoiListDiscoverData - getPoiNearLocation - URL: '.$routepath;
        list( $places_array, $poicount ) = $this->get('ReviewsServices')->getPoiListDiscoverData( $this->data['LanguageGet'], $c_id, $co_id, $statename, 0, 40, $url_source );
        
        $this->data['poicount'] = $poicount;
        $this->data['places_array']       = $places_array;
        $this->data['media_link']         = $medialink;
        $this->data['structurePlaceType'] = $structurePlaceType;
        return $this->render('default/where-is-city.twig', $this->data);
    }

    public function returnTopThingstodoLink($id)
    {
        $aliasInfo = $this->get('TTServices')->aliasContentInfo($id);
        if (sizeof($aliasInfo)) {
            return $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/'.$aliasInfo['a_alias']);
        } else {
            return '';
        }
    }

    public function LanguageGet()
    {
        global $GLOBAL_LANG;
        if (!isset($GLOBAL_LANG) || !$GLOBAL_LANG) $GLOBAL_LANG = 'en';
        return $GLOBAL_LANG;
    }

    public function generateLangRoute($route, $request = array())
    {
        $lang  = $this->data['LanguageGet'];
        if ($lang != 'en') $route .= '_lang';
        return $this->generateUrl($route, $request);
    }

    public function redirectToLangRoute($route, array $parameters = array(), $status = 302)
    {
        $lang  = $this->data['LanguageGet'];
        if ($lang != 'en') $route .= '_lang';
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    public function userIsCorporateAccount()
    {
        if (!$this->data['isUserLoggedIn'] || !$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return false;
        }

        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $userInfo['isCorporateAccount'];
    }

    public function getCorporateSubAccounts($associative_result = true)
    {
        if (!$this->userIsCorporateAccount()) return array();

        $em = $this->getDoctrine()->getManager();

        $sub_accounts = array();

        $connection = $em->getConnection();
        $statement  = $connection->prepare('SELECT fn_json_sub_corporate_accounts(:user_id, :associative_result) AS sub_accounts');
        $statement->bindValue(':user_id', $this->userGetID(), \PDO::PARAM_INT);
        $statement->bindValue(':associative_result', ($associative_result), \PDO::PARAM_BOOL);
        $statement->execute();

        $sub_accounts = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($sub_accounts !== false) {
            $sub_accounts = $sub_accounts[0]['sub_accounts'];

            if ($sub_accounts) $sub_accounts = json_decode($sub_accounts, true);
        }

        $statement->closeCursor();

        if (!$sub_accounts) $sub_accounts = array();

        return $sub_accounts;
    }

    public function userGetName($display_options = array('max_length' => 17))
    {
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $this->get('app.utils')->returnUserArrayDisplayName($userInfo, $display_options);
    }

    public function getUserEmail()
    {
        if (!$this->data['isUserLoggedIn']) {
            return '';
        }
        if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return '';
        }
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $userInfo['email'];
    }

    public function getUserName()
    {
        if (!$this->data['isUserLoggedIn']) {
            return '';
        }
        if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return '';
        }
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return $userInfo['YourUserName'];
    }

    public function userGetID()
    {
        if (!$this->data['isUserLoggedIn']) {
            return false;
        }
        if (!$this->get('ApiUserServices')->tt_global_isset('userInfo')) {
            return false;
        }
        $userInfo = $this->get('ApiUserServices')->tt_global_get('userInfo');
        return intval($userInfo['id']);
    }

    public function userLogout()
    {
        $em          = $this->getDoctrine()->getManager();
        global $request;
        $lt_cookie   = $request->cookies->get('lt', '');
        $login_token = $lt_cookie;

        $qb         = $em->createQueryBuilder('UP')
            ->delete('TTBundle:CmsTubers', 'v')
            ->where("v.uid = :Login_Token")
            ->setParameter(':Login_Token', $login_token);
        $query      = $qb->getQuery();
        $query->getResult();
        $pathcookie = '/';
        setcookie("lt", '', time() - 3600, $pathcookie, $this->container->getParameter('CONFIG_COOKIE_PATH'));
        $this->get('UserServices')->userEndSession(session_id());
        $session    = $this->getRequest()->getSession();
        $ses_vars   = $session->all();
        foreach ($ses_vars as $key => $value) {
            $session->remove($key);
        }
        $session->clear();
        session_destroy();
    }

    public function hotelsInAction($dest, $srch = '', $seotitle, $seodescription, $seokeywords)
    {
        $parameters = array(
            'dest' => $dest,
            'srch' => $srch,
            'seotitle' => $seotitle,
            'seodescription' => $seodescription,
            'seokeywords' => $seokeywords
        );
        return $this->allHotelsIn($parameters);
    }

    public function attractionsNearByRedirectAction($dest, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_attractions_near', array('dest' => $dest), 301);
    }

    public function attractionsNearByAction($dest, $seotitle, $seodescription, $seokeywords)
    {
        $request                = Request::createFromGlobals();
        $routepath              = $this->getRoutePath($request);
        $pageType               = 3;
        $this->data['pageType'] = $pageType;
        $destination_str        = explode('-', $dest);
        $poidest                = explode('_', $dest);
        if (isset($poidest[2])) {
            $page = $poidest[2];
        } else {
            $page = 1;
        }
        $maxPage = $this->container->getParameter('MAX_RECORD');

        if ($page <= 1) {
            $from = 0;
        } elseif ($page > 1) {
            $from = ($page - 1) * 10;
        } elseif ($page >= $maxPage) {
            $from = ($maxPage - 1) * 10;
        }

        if (!isset($poidest[1])) return $this->pageNotFoundAction();
        $poi_id        = $poidest[1];
        $poiInfo       = $this->get('ReviewsServices')->getPoiInfo($poi_id);
        $country_array = $this->get('CmsCountriesServices')->countryGetInfo($poiInfo[0]->getCountry());

        $restaurantsnearlink = '';
        $hotelsnearlink      = $this->get('TTRouteUtils')->returnHotelsNearByLink($this->data['LanguageGet'], $poiInfo[0]->getName(), $poi_id, 1);

        $restaurantsnearname = '';
        $hotelsnearname      = 'hotels near by '.$poiInfo[0]->getName();

        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_array[]         = $this->get('app.utils')->htmlEntityDecode($poiInfo[0]->getName()).' part '.$page;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_array[]               = $this->get('app.utils')->htmlEntityDecode($poiInfo[0]->getName());
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $this->get('app.utils')->htmlEntityDecode($poiInfo[0]->getName());
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $countrybreadcrumb = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
        //$lknameco = str_replace('-', '+', $countrybreadcrumb);
        $lknameco          = $this->get('app.utils')->cleanTitleData($countrybreadcrumb);
        $pidco             = 'CO_'.$country_array->getCode();

        $countrybreadcrumblink               = '';
        $countrybreadcrumb                   = '';
        $this->data['countrybreadcrumb']     = $countrybreadcrumb;
        $this->data['countrybreadcrumblink'] = $countrybreadcrumblink;

        $citybreadcrumblink = '';
        $cityNamebreadcrumb = $cityName           = '';
        if ($poiInfo[0]->getCityId() != 0) {
            $city         = $cityId       = $poiInfo[0]->getCityId();
            $city_info    = $this->get('CitiesServices')->worldcitiespopInfo($city);
            $cityName     = $this->get('app.utils')->htmlEntityDecode($city_info[0]->getName());
            $country_code = $poiInfo[0]->getCountry();

            $cityNamebreadcrumb = '';
            //$lknameco = str_replace('-', '+', $cityName);
            $lknameco           = $this->get('app.utils')->cleanTitleData($cityName);
            $pidco              = 'C_'.$cityId;
            $citybreadcrumblink = '';
        }
        $this->data['cityNamebreadcrumb'] = $cityNamebreadcrumb;
        $this->data['citybreadcrumblink'] = $citybreadcrumblink;

        $poi_latitude  = $poiInfo[0]->getLatitude();
        $poi_longitude = $poiInfo[0]->getLongitude();

        $srch_options    = array
            (
            'limit' => 10,
            'from' => $from,
            'countryCode' => $poiInfo[0]->getCountry(),
            'sortByLat' => $poi_latitude,
            'sortByLon' => $poi_longitude,
            'sortBy' => 'location.coordinates',
            'search_try' => 2,
            'sortGeolocation' => 1
        );
        $url_source      = 'attractionsNearByAction - getPoiNearLocation - poiSearch - URL: '.$routepath;
        $poiNearLocation = $this->get('ElasticServices')->getPoiNearLocation($srch_options, $url_source);

        $ret   = $poiNearLocation[0];
        $count = $poiNearLocation[1];

        $country_name         = '';
        $search_paging_output = '';
        $apoi                 = array();
        $poi_array            = array();
        $locationText         = '';
        foreach ($ret as $poi) {
            $locationText = '';
            if (count($poi['_source']['images']) > 0) {
                $dimagepath    = 'media/discover/';
                $dimage        = $poi['_source']['images'][0]['filename'];
                $apoi['image'] = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 291, 166, 'poi-in-291166');
            } else {
                $image = $this->get('ReviewsServices')->getPoiDefaultPic($poi['_source']['id']);
                if ($image) {
                    $dimagepath          = 'media/discover/';
                    $dimage              = $image->getFilename();
                    $image_pa            = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 291, 166, 'poi-in-291166');
                    $apoi['image']       = $image_pa;
                    $apoi['image_exist'] = true;
                } else {
                    $apoi['image']        = $this->get("TTRouteUtils")->generateMediaURL('/media/images/landmark-icon3.jpg');
                    $apoi['image_exist']  = FALSE;
                    $apoi['image_width']  = '';
                    $apoi['image_height'] = '';
                }
            }
            $apoi['title']    = $this->get('app.utils')->htmlEntityDecode($poi['_source']['name']);
            $apoi['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($poi['_source']['name']);
            $apoi['link']     = '';
            $locationTextSeo  = '';
            $cityTextSeo      = '';
            $locationTextSeo  = '';

            if (intval($poi['_source']['location']['city']['id']) > 0) {
                $city_array = $this->get('CitiesServices')->worldcitiespopInfo(intval($poi['_source']['location']['city']['id']));
                $city_array = $city_array[0];
                $city_name  = $this->get('app.utils')->htmlEntityDecode($city_array->getName());
                if ($city_name) {
                    if ($locationText) $locationText    .= '<br/>';
                    $locationText    .= $city_name;
                    $locationTextSeo .= $city_name;
                    $cityTextSeo     .= $city_name;
                }
                $state_name  = '';
                $state_array = $this->get('CitiesServices')->worldStateInfo($city_array->getCountryCode(), $city_array->getStateCode());
                if ($state_array && sizeof($state_array)) {
                    $state_name = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                    if ($state_name) {
                        if ($city_name == '') $locationText    .= '<br/>';
                        $locationText    .= ', '.$state_name;
                        $locationTextSeo .= ' '.$state_name;
                    }
                }
                $country_array = $this->get('CmsCountriesServices')->countryGetInfo($city_array->getCountryCode());
                $country_name  = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
                if ($country_name) {
                    if ($city_name == '' && $state_name == '') $locationText    .= '<br/>';
                    $locationText    .= ', '.$country_name;
                    $locationTextSeo .= ' '.$country_name;
                }
            } else {
                $locationText    = $poi['_source']['location']['address'];
                $locationTextSeo = $poi['_source']['location']['address'];
            }
            if ($locationText == '') {
                $locationText = $poi['_source']['location']['address'];
            } else if ($poi['_source']['location']['address']) {
                $locationText .= '<br>'.$poi['_source']['location']['address'];
            }
            if ($poi['_source']['phone']) {
                if ($locationText) $locationText .= '<br/>';
                $locationText .= $poi['_source']['phone'];
            }
            $apoi['stars']    = 0;
            $apoi['location'] = $locationText;

            $apoi['detail_link'] = $this->get('TTRouteUtils')->returnThingstodoReviewLink($this->data['LanguageGet'], $poi['_source']['id'], $this->get('app.utils')->htmlEntityDecode($poi['_source']['name']));
            $apoi['show_on_map'] = '/ajax/show-on-map?type=p&id='.$poi['_source']['id'];
            $apoi['id']          = $poi['_source']['id'];
            $apoi['type']        = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK');
            $apoi['country']     = $poi['_source']['location']['country']['name'];
            $apoi['city']        = $poi['_source']['location']['city']['id'];
            $poi_array[]         = $apoi;
        }
        $number_of_pages = $count / 10;
        $number_of_pages = ceil($number_of_pages);

        $countrybreadcrumblink = '';
        $countrybreadcrumb     = '';
        $citybreadcrumblink    = '';
        $cityNamebreadcrumb    = '';
        $qr                    = '';
        $type                  = 0;

        $this->data['city']                   = $qr;
        $this->data['dest']                   = $dest;
        $this->data['type']                   = $type;
        $this->data['country']                = $country_name;
        $this->data['total']                  = $count;
        $this->data['totalpage']              = $count;
        $this->data['paging']                 = $search_paging_output;
        $this->data['countrybreadcrumblink']  = $countrybreadcrumblink;
        $this->data['countrybreadcrumb']      = $countrybreadcrumb;
        $this->data['citybreadcrumblink']     = $citybreadcrumblink;
        $this->data['cityNamebreadcrumb']     = $cityNamebreadcrumb;
        $this->data['hotels_array']           = $poi_array;
        $this->data['entity_type']            = $this->container->getParameter('SOCIAL_ENTITY_LANDMARK');
        $this->data['hotel_prefrences']       = array();
        $this->data['hotel_prefrencesKey']    = $this->translator->trans('Category');
        $this->data['hide_stars']             = 1;
        $this->data['hide_filter']            = 1;
        $this->data['hotel_property_type']    = array();
        $this->data['hotel_property_typeKey'] = '';
        $this->data['descHotelsinTitle']      = '';
        $this->data['page_title']             = $this->translator->trans('Attractions near by').' '.$poiInfo[0]->getName();

        return $this->render('default/hotels.in.twig', $this->data);
    }

    public function displayViewsCount($num, $add_num = 1)
    {
        if (intval($num) > 1 || intval($num) == 0) {
            $data_val = $this->translator->trans('views');
        } else {
            $data_val = $this->translator->trans('view');
        }
        if ($add_num) {
            return $this->displayValueNum($num).' '.$data_val;
        } else {
            return $data_val;
        }
    }

    public function displayReviewsCount($num, $add_num = 1)
    {
        if (intval($num) > 1 || intval($num) == 0) {
            $data_val = $this->translator->trans('reviews');
        } else {
            $data_val = $this->translator->trans('review');
        }
        if ($add_num) {
            return $this->displayValueNum($num).' '.$data_val;
        } else {
            return $data_val;
        }
    }

    public function displayRatingsCount($num, $add_num = 1)
    {
        if (intval($num) > 1 || intval($num) == 0) {
            $data_val = $this->translator->trans('ratings');
        } else {
            $data_val = $this->translator->trans('rating');
        }
        if ($add_num) {
            return $this->displayValueNum($num).' '.$data_val;
        } else {
            return $data_val;
        }
    }

    public function displayValueNum($num)
    {
        if (intval($num) < 0) {
            $num = 0;
        }
        return $this->tt_number_format($num);
    }

    public function tt_number_format($in)
    {
        if ($in == '') return 0;
        if ($in == 0) return 0;
        $out = intval($in);
        if ($out >= 1000) {
            $out = intval($out / 100);
            $out = $out / 10;
            $out .= 'k';
        }
        return $out;
    }

    /**
     * return string into parts defined by some rules. If the rules do not apply, return the input string itself.
     *
     * Example: get_string_parts('282659281446030124.png', 5, '\.', null) returns ('28265', '92814', '46030', '124', '.', 'png')
     *
     * @param string $input_string The input string.
     * @param string $part_length Defines the length of each string part.
     * @param string $delimiter This character will show in the array as a delimiter (single array element) when it's encountered in the input string. This parameter is currently passed as is to a regular expression matching function. TODO:: add regexp-specific auto-escaping when necessary.
     * @param array(string, string, ...) suppress_prefixes An array of characters to be suppressed. Must be fixed, but for the time being, we don't rely on this parameter.
     *
     * @return string Array of string parts, or the input string if the given rules do not apply.
     */
    public function get_string_parts($input_string, $part_length = 4, $delimiter = '@', $suppress_prefixes = array('.'))
    {
        if ($part_length < 1) $part_length = 1;

        // $r = "/(.+?(?=@)|[^.@]+)/"; //  ==> L1234567890 for L1234567890@idm.net.lb
        // $r = "/(?(?=@).|[^.@][^@]{1,3})+?/";

        $r = "/(?(?=$delimiter).|[^".($suppress_prefixes ? implode('', $suppress_prefixes) : '')."$delimiter][^$delimiter]{0,".($part_length - 1)."})+?/";

        if (preg_match_all($r, $input_string, $matches, PREG_SET_ORDER)) {
            $string_parts = array();

            foreach ($matches as $string_match)
                $string_parts[] = $string_match[0];

            return $string_parts;
        }

        return $input_string;
    }

    /**
     * Return the directory path given a base_path, string_parts (as returned by function get_string_parts), and some options.
     *
     * @param string $base_path The base path for the directory.
     * @param string $string_parts The output from function get_string_parts().
     * @param string $options Options such as: stop_at_part (default: '') This is usually the delimiter character specified in the call to get_string_parts, inclusive_stop_part (default: false) Tells whether the character to stop at should be included in the directory path string.
     *
     * @return string Directory path constructed using the given parameters.
     */
    public function get_folder_path_from_string_parts($base_path, $string_parts, $options)
    {
        $default_options = array('stop_at_part' => '', 'inclusive_stop_part' => false);

        if (!$options) $options = array();
        if (!is_array($options)) $options = array($options);
        $options = array_merge($default_options, $options);

        $folder_path = $base_path;
        if ($folder_path && substr($folder_path, strlen($folder_path) - 1) != DIRECTORY_SEPARATOR) $folder_path .= DIRECTORY_SEPARATOR;

        foreach ($string_parts as $string_part) {
            if ($string_part == $options['stop_at_part'] && !$options['inclusive_stop_part']) break;

            $folder_path .= $string_part.'/';

            if ($string_part == $options['stop_at_part']) break;
        }

        return $folder_path;
    }

    public function getRoutePath(Request $request, $last = false, $page = 1)
    {
        $pathinfo = $request->getPathInfo();
        global $GLOBAL_LANG;
        if ($GLOBAL_LANG != '') {
            $linksArray = explode('/'.$GLOBAL_LANG.'/', $pathinfo);
            if (sizeof($linksArray) > 1) {
                $pathinfo = '/'.$linksArray[1];
            }
        }
        $pathinfo = explode('/', $pathinfo);
        if ($last) {
            $path = $pathinfo[sizeof($pathinfo) - $page];
        } else {
            $path = $pathinfo[1];
        }
        return $path;
    }

    public function userProfileLink($userInfo, $is_array = false, $is_email = 0)
    {
        $userId = $this->userGetID();
        if ($is_array) {
            if ($this->data['isUserLoggedIn'] && ( $userInfo['u_id'] && $userInfo['u_id'] == $userId) && $is_email == 0) {
                return $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/myprofile');
            } else {
                return $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/profile/'.$userInfo['u_yourusername']);
            }
        } else {
            if ($this->data['isUserLoggedIn'] && ( $userInfo->getId() && $userInfo->getId() == $userId) && $is_email == 0) {
                return $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/myprofile');
            } else {
                return $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], '/profile/'.$userInfo->getYourUserName());
            }
        }
    }

    public function channelGlobalGet()
    {
        global $_global_channel;

        return (!$_global_channel ? false : $_global_channel);
    }

    public function channelGlobalSet($cinfo)
    {
        global $_global_channel;
        $_global_channel = $cinfo;
    }

    public function userCurrentChannelGet()
    {
        global $request;
        $current_channel = $request->cookies->get('current_channel', false);
        if ($current_channel != false) {
            return $this->get('ChannelServices')->channelInfoFromURL($current_channel, $this->data['LanguageGet']);
        }
        return false;
    }

    public function strip_prefix($str, $prefix, $lowerFirstChar = true)
    {
        if (substr($str, 0, strlen($prefix)) != $prefix) return $str;

        $out_string = substr($str, strlen($prefix));

        if ($lowerFirstChar) $out_string = lcfirst($out_string);

        return $out_string;
    }

    public function flatten_array($in)
    {
        if (!is_array($in)) $in = array($in);

        $out = array();

        foreach ($in as $element) {
            if (!$element) continue;

            $normalized_class_name = get_class($element);

            if (strpos($normalized_class_name, '\\') !== false) $normalized_class_name = substr($normalized_class_name, strrpos($normalized_class_name, '\\') + 1);

            $normalized_class_name = preg_replace_callback("/([A-Z])/", function($matches) {
                return '_'.strtolower($matches[1]);
            }, lcfirst($normalized_class_name));

            $class_methods = get_class_methods($element);
            if ($class_methods) {
                foreach ($class_methods as $class_method) {
                    $pos = strpos($class_method, 'get');

                    if ($pos !== false && $pos == 0) {
                        $property_name = $this->strip_prefix($class_method, 'get');

                        $out[(array_key_exists($property_name, $out) ? $normalized_class_name.'_'.$property_name : $property_name)] = call_user_func(array($element,
                            $class_method));
                    }
                }
            }
        }

        return $out;
    }

    public function returnHotelSearchLink($city, $country, $state, $data = array())
    {
        if ($state == 0) $state = "";

        if ($country != "" && $state != "" && $city == 0) {

            $state_name = '';

            if ($data && isset($data['state']['name'])) $state_name = $data['state']['name'];
            else {
                $state_info = $this->get('CitiesServices')->worldStateInfo($country, $state);

                if ($state_info && count($state_info)) $state_name = $state_info[0]->getStateName();
            }

            $url = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $state_name, 0, $state, $country, 1);
        } elseif ($country != "" && $state == "" && $city == 0) {

            $country_name = '';

            if ($data && isset($data['country']['name'])) $country_name = $data['country']['name'];
            else {
                $country_array = $this->get('CmsCountriesServices')->countryGetInfo($country);
                $country_name  = $country_array->getName();
            }

            $url = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $country_name, 0, '', $country, 1);
        } else {

            $city_name = '';

            if ($data && isset($data['city']['name'])) $city_name = $data['city']['name'];
            else {
                $city_info = $this->get('CitiesServices')->worldcitiespopInfo($city);

                if ($city_info && count($city_info)) $city_name = $city_info[0]->getName();
            }

            $url = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $city_name, $city, '', '', 1);
        }

        return $url;
    }

    public function returnThingsToDoSearchLink($city, $country, $state, $data = array())
    {
        if ($state == 0) $state = "";

        if ($country != "" && $state != "" && $city == 0) {

            $state_name = '';

            if ($data && isset($data['state']['name'])) $state_name = $data['state']['name'];
            else {
                $state_info = $this->get('CitiesServices')->worldStateInfo($country, $state);

                if ($state_info && count($state_info)) $state_name = $state_info[0]->getStateName();
            }

            // $state_name = str_replace('-', '+', $state_name);
            $state_name = $this->get('app.utils')->cleanTitleData($state_name);
            $url        = "Attractions-in-".$state_name."-S_".$state."_".$country."_1";
        } elseif ($country != "" && $state == "" && $city == 0) {

            $country_name = '';

            if ($data && isset($data['country']['name'])) $country_name = $data['country']['name'];
            else {
                $country_array = $this->get('CmsCountriesServices')->countryGetInfo($country);
                $country_name  = $country_array->getName();
            }

            // $country_name = str_replace('-', '+', $country_name);
            $country_name = $this->get('app.utils')->cleanTitleData($country_name);
            $url          = "Attractions-in-".$country_name."-CO_".$country."_1";
        } else {

            $city_name = '';

            if ($data && isset($data['city']['name'])) $city_name = $data['city']['name'];
            else {
                $city_info = $this->get('CitiesServices')->worldcitiespopInfo($city);
                // $city_name = str_replace('-', '+', $city_name);
                if ($city_info && count($city_info)) {
                    $city_name = $city_info[0]->getName();
                    $city_name = $this->get('app.utils')->cleanTitleData($city_name);
                }
            }

            $url = "Attractions-in-".$city_name."-C_".$city."_1";
        }

        return $this->get('app.utils')->generateLangURL($this->data['LanguageGet'], $url);
    }

    public function checkUserPrivacyExtand($user_id, $from_id, $mediaInfo)
    {
        if ($user_id == $from_id) {
            return true;
        }

        if (intval($mediaInfo['v_isPublic']) == $this->container->getParameter('USER_PRIVACY_PUBLIC')) {
            return true;
        } else {
            return false;
        }
    }

    public function checkExistFromId($id, $array)
    {
        if (in_array($id, $array)) {
            return true;
        } else {
            return false;
        }
    }

    public function getOriginalPP($link)
    {
        if (strpos($link, 'Profile_') == 0) {
            $link = substr($link, 8);
        }
        return $link;
    }

    public function photoReturnchannelLogo($channelInfo)
    {
        if ($channelInfo->getLogo() == '') {
            return $this->get("TTRouteUtils")->generateMediaURL('/media/tubers/tuber.jpg');
        } else {
            return $this->get("TTRouteUtils")->generateMediaURL('/media/channel/'.$channelInfo->getId().'/thumb/'.$channelInfo->getLogo());
        }
    }

    public function photoReturnchannelLogoBig($channelInfo)
    {
        if ($channelInfo['c_logo'] == '') {
            return $this->get("TTRouteUtils")->generateMediaURL('/media/tubers/tuber.jpg');
        } else {
            return $this->get("TTRouteUtils")->generateMediaURL('/media/channel/'.$channelInfo['c_id'].'/'.$channelInfo['c_logo']);
        }
    }

    public function foreTechHotelsTesterAction(Request $request)
    {
        ob_start();
        include_once "fore_tech_hotels_tester.php";
        $response = ob_get_clean();
        return new Response($response);
    }

    public function returnSearchLinksArray($link_options = array())
    {
        $default_opts = array(
            'link_name' => '',
            'city_id' => 0,
            'state_code' => '',
            'country_code' => '',
            'state_name' => '',
            'country_name' => '',
            'city_name' => '',
            'route_path' => '',
            'type' => array('where_is', 'discover', 'hotels_in', 'channels')
        );
        $options      = array_merge($default_opts, $link_options);

        $link_name = $options['link_name'];
        if ($link_name == '') {
            return array();
        }
        $cityId      = intval($options['city_id']);
        $stateCode   = $options['state_code'];
        $countryCode = $options['country_code'];
        $stateName   = $options['state_name'];
        $countryName = $options['country_name'];
        $cityName    = $options['city_name'];
        $routepath   = $options['route_path'];
        $type_list   = $options['type'];

        $search_array = array();

        $tmp_data_array['state']           = array();
        $tmp_data_array['state']['name']   = $stateName;
        $tmp_data_array['country']         = array();
        $tmp_data_array['country']['name'] = $countryName;
        $tmp_data_array['city']            = array();
        $tmp_data_array['city']['name']    = $cityName;

        foreach ($type_list as $types) {
            $push_to_array = true;
            $dataName      = '';
            $dataNamealt   = '';
            $dataLink      = '';

            switch ($types) {
                case 'where_is':
                    $dataName    = $this->translator->trans('Where is').' '.$link_name;
                    $dataNamealt = $this->get('app.utils')->cleanTitleDataAlt($dataName);
                    $dataLink    = $this->get('TTRouteUtils')->returnWhereIsLink($this->data['LanguageGet'], $link_name, $cityId, $stateCode, $countryCode);
                    break;

                case 'discover':
                    $dataName    = $this->translator->trans('Discover').' '.$link_name;
                    $dataNamealt = $this->get('app.utils')->cleanTitleDataAlt($dataName);
                    $dataLink    = $this->get('TTRouteUtils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $link_name, $cityId, $stateCode, $countryCode);
                    break;

                case 'hotels_in':
                    $push_to_array = false;

                    $options           = array(
                        'country_code' => $countryCode,
                        'id' => $cityId
                    );
                    $hotelSelectedCity = $this->get("HRSServices")->getHotelSelectedCityId($options);

                    if ($hotelSelectedCity) {
                        $push_to_array = true;
                        $dataName      = $this->translator->trans('Hotels in').' '.$link_name;
                        $dataNamealt   = $this->get('app.utils')->cleanTitleDataAlt($dataName);
                        $dataLink      = $this->returnHotelSearchLink($cityId, $countryCode, '', $tmp_data_array);
                    }
                    break;

                case 'channels':
                    $push_to_array = false;
                    $channelcount  = $this->get('ChannelServices')->getCityChannelCount($countryCode, $stateCode, $cityId);
                    if ($channelcount > 0) {
                        $push_to_array = true;
                        $dataName      = $this->translator->trans('Channels about').' '.$link_name;
                        $dataNamealt   = $this->get('app.utils')->cleanTitleDataAlt($dataName);
                        $dataLink      = $this->get('TTRouteUtils')->returnChannelsSearchLink($this->data['LanguageGet'], '', $cityId, $stateCode, $countryCode);
                    }
                    break;
            }

            if ($push_to_array) {
                $search_array[] = array('name' => $dataName, 'namealt' => $dataNamealt, 'link' => $dataLink, 'type' => $types);
            }
        }

        return $search_array;
    }

    public function checkTimeZoneCookie()
    {
        global $request;
        $timezone_cookie = $request->cookies->get('timezone', '');
        if (!isset($timezone_cookie)) {
            $expiret    = time() + 365 * 24 * 3600;
            $expire     = gmdate("r", $expiret);
            $pathcookie = '/';
            return '<script type="text/javascript">
                if (navigator.cookieEnabled) document.cookie = "timezone="+ (- new Date().getTimezoneOffset()) +";expires=('.$expire.');path=/";
                if (navigator.cookieEnabled) document.location.reload();
            </script>';
        } else {
            return $this->updateCheckTimeZoneCookie();
        }
    }

    public function updateCheckTimeZoneCookie()
    {
        $expiret    = time() + 365 * 24 * 3600;
        $expire     = gmdate("r", $expiret);
        $pathcookie = '/';
        return '<script type="text/javascript">
             if (navigator.cookieEnabled) document.cookie = "timezone="+ (- new Date().getTimezoneOffset()) +";expires=('.$expire.');path=/";
        </script>';
    }

    public function pageNotFoundAction()
    {
        $url = $this->get('TTRouteUtils')->UriCurrentPageURL();
        $this->get('TTServices')->addPageNotFound($url); // returns $n_times_seen
        throw $this->createNotFoundException('Not found');
    }

    public function secureLoginAction()
    {
        return $this->redirectToLangRoute('_log_in', array(), 301);
    }

    public function getDiscountDisplayableInfo($discount_id, $discount_currency, $currency)
    {
        $discount_details = $this->get('TTServices')->getDiscountDetails($discount_id);

        $effective_discount_value  = number_format($discount_details['d_discountValue'], 2);
        $effective_threshold_value = $discount_details['d_thresholdValue'];

        if ($discount_currency != $currency) {

            $currencyService = $this->get('CurrencyService');

            $conversion_rate = $currencyService->getConversionRate($discount_currency, $currency);

            if ($discount_details['dt_name'] == 'fixed')
                    $effective_discount_value = $currencyService->currencyConvert($effective_discount_value, $conversion_rate); // effective discount value (d.discount_value converted to currency $currency)

            $effective_threshold_value = $currencyService->currencyConvert($effective_threshold_value, $conversion_rate); // effective threshold value (d.threshold_value converted to currency $currency)
        }

        $discount_details['currency_code'] = $currency;

        $discount_details['discount_value'] = number_format($effective_discount_value, 2);

        if ($discount_details['dt_name'] == 'fixed') $discount_details['discount_value_string'] = $discount_details['currency_code'].' '.$discount_details['discount_value'];

        if ($discount_details['dt_name'] == 'percentage') {
            $discount_details['discount_value']        .= '%';
            $discount_details['discount_value_string'] = $discount_details['discount_value'];
        } else if ($discount_details['dt_discountSpecs']) {
            // TODO:: implement specific code when needed
            $discount_details['discount_value_string'] = $discount_details['discount_value'];
        }

        return $discount_details;
    }

    public function valueExists($value, $searchSpecs, $creation_date = null)
    {
        if (!$searchSpecs || ($searchSpecs && !is_array($searchSpecs))) return false;

        if (!isset($searchSpecs['table_name']) || !$searchSpecs['table_name'] || !isset($searchSpecs['field_name']) || !$searchSpecs['field_name']) return false;

        $searchSpecs['table_name'] = ucfirst(preg_replace_callback("/_([a-zA-Z])/", function($matches) {
                return strtoupper($matches[1]);
            }, $searchSpecs['table_name']));
        $searchSpecs['field_name'] = preg_replace_callback("/_([a-zA-Z])/", function($matches) {
            return strtoupper($matches[1]);
        }, $searchSpecs['field_name']);

        if (isset($searchSpecs['creation_date']))
                $searchSpecs['creation_date'] = preg_replace_callback("/_([a-zA-Z])/", function($matches) {
                return strtoupper($matches[1]);
            }, $searchSpecs['creation_date']);
        else $searchSpecs['creation_date'] = 'creationDate';

        $conditionalStatement = "";
        $conditionalParams    = array();

        if (isset($searchSpecs['conditional_clause'])) {
            if (!is_array($searchSpecs['conditional_clause'])) $searchSpecs['conditional_clause'] = array($searchSpecs['conditional_clause']);

            foreach ($searchSpecs['conditional_clause'] as $fieldIndex => $fieldSpecs) {
                if (!isset($fieldSpecs['field_name']) || !$fieldSpecs['field_name'] || !isset($fieldSpecs['field_value'])) continue;

                $fieldSpecs['field_name'] = preg_replace_callback("/_([a-zA-Z])/", function($matches) {
                    return strtoupper($matches[1]);
                }, $fieldSpecs['field_name']);

                $relationalOperator = '=';

                if (isset($fieldSpecs['check_type']) && $fieldSpecs['check_type']) {
                    switch ($fieldSpecs['check_type']) {
                        case 'not_equals':
                            $relationalOperator = '<>';
                            break;
                        default:
                            break;
                    }
                }

                $conditionalStatement = ' AND t.'.$fieldSpecs['field_name'].$relationalOperator.' :'.$fieldSpecs['field_name'].$fieldIndex;

                $conditionalParams[':'.$fieldSpecs['field_name'].$fieldIndex] = $fieldSpecs['field_value'];
            }
        }

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT COUNT(t.'.$searchSpecs['field_name'].') FROM TTBundle:'.$searchSpecs['table_name'].' t WHERE t.'.$searchSpecs['field_name']." = :v $conditionalStatement".($creation_date
                ? " AND (t.userId = 37 OR t.".$searchSpecs['creation_date']." > :creation_date)" : ""));
        $query->setParameter(':v', $value);

        if ($conditionalParams) {
            foreach ($conditionalParams as $parameter_name => $parameter_value)
                $query->setParameter($parameter_name, $parameter_value);
        }

        if ($creation_date) $query->setParameter(':creation_date', $creation_date);

        return ($query->getSingleScalarResult() != 0);
    }
    /*
      Check if the given coupon_code is valid and unused according to any active campaign targetting target_entity_type_id and return the campaign. Otherwise, return false.
     */

    public function validUnusedCouponsCampaign($coupon_code, $target_entity_type_id)
    {
        if (!$coupon_code) return false;

        $activeCampaigns = $this->get('TTServices')->activeCampaigns(array('target_entity_type_id' => $target_entity_type_id), false);

        if ($activeCampaigns === false || !$activeCampaigns) return false;

        foreach ($activeCampaigns as $campaign) {
            $coupon_source = $this->get('TTServices')->getCouponSource($campaign['c_sourceEntityTypeId']);
            // $source_specs = json_decode($coupon_source['cs_sourceSpecs'], true);
            $source_specs  = $coupon_source['cs_sourceSpecs'];

            $couponExists = $this->valueExists($coupon_code, $source_specs, $campaign['c_startDate']);

            // $this->debug("validUnusedCouponsCampaign:: valueExists(coupon_code:: $coupon_code, source_specs:: " . print_r($source_specs, true) . ", creation_date:: " . print_r($campaign['c_startDate'], true) . ") ? " . $couponExists);

            if ($couponExists) {
                if ($this->get('TTServices')->isCouponUsed($campaign['c_id'], $coupon_code)) {
                    return false;
                } else {
                    if ($coupon_source['cs_helperText']) $campaign['target_helper_text'] = $coupon_source['cs_helperText'];

                    return $campaign;
                }
            }
        }

        return false;
    }

    public function applyDiscount($discount_id, $discount_currency, $currency, $amount)
    {
        $discounted_amount = $amount;

        $discount_details = $this->get('TTServices')->getDiscountDetails($discount_id);
        // $this->debug("applyDiscount:: discount_details:: " . print_r($discount_details, true));

        $effective_discount_value  = $discount_details['d_discountValue'];
        $effective_threshold_value = $discount_details['d_thresholdValue'];

        if ($discount_currency != $currency) {
            $currencyService = $this->get('CurrencyService');

            $conversion_rate = $currencyService->getConversionRate($discount_currency, $currency);

            if ($discount_details['dt_name'] == 'fixed')
                    $effective_discount_value = $currencyService->currencyConvert($effective_discount_value, $conversion_rate); // effective discount value (d.discount_value converted to currency $currency)

            $effective_threshold_value = $currencyService->currencyConvert($effective_threshold_value, $conversion_rate); // effective threshold value (d.threshold_value converted to currency $currency)
        }

        $eligibleForDiscount = ($amount >= $effective_threshold_value);

        if ($eligibleForDiscount) {
            if ($discount_details['dt_name'] == 'fixed') $discounted_amount -= $effective_discount_value;
            else if ($discount_details['dt_name'] == 'percentage') $discounted_amount -= ($discounted_amount * $effective_discount_value) / 100;
            else if ($discount_details['dt_discountSpecs']) {
                $discount_specs = $discount_details['dt_discountSpecs'];

                $function_parameters = array();

                foreach ($discount_specs['function_parameters'] as $parameter) {
                    if ($parameter == 'discount_value') $function_parameters[] = $effective_discount_value;
                    else if ($parameter == 'amount') $function_parameters[] = $amount;
                    else $function_parameters[] = $parameter; // literal value
                }

                $discounted_amount -= call_user_func_array(array($this, $discount_specs['function_name']), $function_parameters);
            }
        }

        return array('amount' => $discounted_amount, 'status' => $eligibleForDiscount);
    }

    public function debug($val)
    {
        $this->get('app.utils')->debug($val);
    }

    public function is_arabic($str)
    {
        if (mb_detect_encoding($str) !== 'UTF-8') {
            $str = mb_convert_encoding($str, mb_detect_encoding($str), 'UTF-8');
        }

        preg_match_all('/.|\n/u', $str, $matches);
        $chars        = $matches[0];
        $arabic_count = 0;
        $latin_count  = 0;
        $total_count  = 0;
        foreach ($chars as $char) {
            $pos = $this->uniord($char);
            if ($pos >= 1536 && $pos <= 1791) {
                $arabic_count++;
            } else if ($pos > 123 && $pos < 123) {
                $latin_count++;
            }
            $total_count++;
        }
        if (($arabic_count / $total_count) > 0.6) {
            return true;
        }
        return false;
    }

    public function uniord($u)
    {
        // i just copied this function fron the php.net comments, but it should work fine!
        $k  = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }

    public function handleResponse($data, $twig = '', $mobile = 0)
    {

        //Handle the JSON response for the mobile app or render the twig normally
        if ($mobile == 1) {
            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            return $this->render($twig, $data);
        }
    }

    /**
     * Check if given route name exists in our routing config as key
     *
     * @param type $name
     * @return type
     */
    protected function isRouteExists($name)
    {
        // I assume that you have a link to the container in your twig extension class
        $router = $this->container->get('router');
        return (null === $router->getRouteCollection()->get($name)) ? false : true;
    }

    /**
     * Check if the given route is key config or full URL in order to make the proper redirection
     *
     * @param route_id or URL $route
     * @param array $params
     * @return type
     */
    protected function redirectDynamicRoute($route, $params = array())
    {
        if ($this->isRouteExists($route)) return $this->redirectToRoute($route, $params);
        else {
            $key = "?";
            if (strpos($route, '?') !== false) $key = "";
            return $this->redirect($route.$key.http_build_query($params));
        }
    }

    /**
     * Manage the payment process and the corporate approval flow
     *
     * @return {paymentId, callbackUrl}
     */
    public function paymentApproval($payment, $moduleId)
    {

        $corporatePage = $this->get('app.utils')->isCorporateSite();

        //if ($this->data['is_corporate_account'] && $corporatePage) {
        if ($this->data['is_corporate_account'] && ($corporatePage || $payment->getTrxTypeId() == $this->container->getParameter('MODULE_FLIGHTS'))) {

            $userId             = $this->userGetID();
            $userArray          = $this->get('UserServices')->getUserDetails(array('id' => $userId));
            $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];

            $reqSerParams = array(
                "userId" => $userId,
                "accountId" => $userCorpoAccountId,
                "moduleId" => $moduleId, //$this->container->getParameter('MODULE_FLIGHTS'),
                "reservationId" => $payment->getModuleTransactionId(),
                "currencyCode" => $payment->getCurrency(),
                "amount" => $payment->getAmount()
            );

            $status    = $this->container->getParameter('CORPO_APPROVAL_PENDING');
            // Create Request Details
            $crsResult = $this->get('CorpoApprovalFlowServices')->addPendingRequestServices($reqSerParams, $status);

            // If allowed user . . .
            $result = array();

            $checkBudgetLimit = $this->get('CorpoAccountServices')->isAccountBudgetAllowed($reqSerParams);
            
            if ($checkBudgetLimit) {

                $sessionData = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();

                if ($sessionData['allowApproval'] || $sessionData['userGroupId'] == $this->container->getParameter('ROLE_SYSTEM')) {

                    $payInit = $this->get('PaymentServiceImpl')->initializePayment($payment);

                    $result["callback_url"]   = $payInit->getCallBackUrl();
                    $result["transaction_id"] = $payInit->getTransactionId();
                } else {

                    $_callback_url          = ($corporatePage) ? "_corporate_account_waiting_approval" : "_waiting_approval";
                    $result["callback_url"] = $_callback_url;

                    //@TODO: Email Templetes is still subject to changes from Fares
                    $corpoEmail = $userArray[0]['corporateEmail'];
                    $userEmail  = $userArray[0]['cu_youremail'];

                    $emailData                = array();
                    $emailData['username']    = $userArray[0]['cu_yourusername'];
                    $emailData['accountName'] = $userArray[0]['accountName'];
                    $userMsg                  = $this->renderView('emails/payment_pending_approval.twig', array('emailData' => $emailData));
                    $accountMsg               = $this->renderView('emails/payment_pending_account_approval.twig', array('emailData' => $emailData));

                    $subject = $this->translator->trans('TouristTube Pending Approval');
                    $title   = $this->translator->trans('TouristTube.com Travel Approval');

                    //sending user a notification
                    $this->get('emailServices')->addEmailData($userEmail, $userMsg, $subject, $title, 0);
                    //sending account manager a notification
                    $this->get('emailServices')->addEmailData($corpoEmail, $accountMsg, $subject, $title, 0);
                }
            } else {

                $payment->setPaymentType(Payment::CREDIT_CARD);
                $payInit = $this->get('PaymentServiceImpl')->initializePayment($payment);

                $result["callback_url"]   = $payInit->getCallBackUrl();
                $result["params"]["msg"]  = $this->translator->trans("Kindly, note that you have reached your budget limit.");
                $result["transaction_id"] = $payInit->getTransactionId();
            }
        } else {

            $payment->setPaymentType(Payment::CREDIT_CARD);
            $payInit = $this->get('PaymentServiceImpl')->initializePayment($payment);

            $result["callback_url"]   = $payInit->getCallBackUrl();
            $result["transaction_id"] = $payInit->getTransactionId();
        }
        return $result;
    }

    protected function addNotification($mssg, $type = null, $delay = null, $title = null)
    {
        $notificationsValue = array(
            "type" => $type,
            "mssg" => $mssg,
            "delay" => $delay,
            "title" => $title
        );
        //
        $notifications[]    = $notificationsValue;
        //
        $this->addFlash($notificationsValue['type'], json_encode($notifications));


        //        $this->data['corporateNotificationArray'] = json_encode($notifications);
    }

    protected function addErrorNotification($mssg, $delay = null, $title = null)
    {
        $this->addNotification($mssg, "error", $delay = null, $title = null);
    }

    protected function addWarningNotification($mssg, $delay = null, $title = null)
    {
        $this->addNotification($mssg, "warning", $delay = null, $title = null);
    }

    protected function addInfoNotification($mssg, $delay = null, $title = null)
    {
        $this->addNotification($mssg, "info", $delay = null, $title = null);
    }

    protected function addSuccessNotification($mssg, $delay = null, $title = null)
    {
        $this->addNotification($mssg, "success", $delay = null, $title = null);
    }

    /**
     * This method cleans the given parameters before logging
     *
     * @param array $params, passed by reference
     *
     */
    public function cleanParams(&$params)
    {
        if (!empty($params['creditCard']['number'])) {
            $params['creditCard']['number'] = str_repeat('*', strlen($params['creditCard']['number']) - 4).substr($params['creditCard']['number'], -4);

            if (isset($params['creditCard']['securityCode'])) {
                $params['creditCard']['securityCode'] = str_repeat('*', strlen($params['creditCard']['securityCode']));
            }
        }
    }

    /**
     * @param array $params associative array
     */
    public function prepareLogParameters(&$params, $cleanParams = false)
    {
        if (!isset($params) || !is_array($params)) $params = array();

        if ($params) {
            if ($cleanParams) {
                $this->cleanParams($params);
            }

            foreach (array_keys($params) as $param_key) {
                $params[$param_key] = json_encode($params[$param_key]);
            }
        }

        $params['userId'] = ($this->data['isUserLoggedIn']) ? $this->userGetID() : 0;
    }

    /**
     * Log message with params, optionally cleaning the params (currently, masking credit card info, if they exist).
     * @param String $message
     * @param array $params Expected key/value pairs, with keys occurring in message, as {key}, {key} will be replaced with its associated value in $params in the logged string.
     * @param boolean $cleanParams
     */
//    public function addErrorLog($message, $params = array(), $cleanParams = false)
//    {
//        $this->prepareLogParameters($params, $cleanParams);
//
//        $logger = $this->get('logger');
//        $logger->error("\nUser {userId} - ".$message, $params);
//    }
//
//    public function checkElasticErrorLog($queryResults)
//    {
//        if (isset($queryResults['error_encountered']) && $queryResults['error_encountered']) {
//            $guid = str_replace('-', '', $this->get('app.utils')->GUID());
//            if (isset($queryResults['criteria'])) {
//                $queryResults['criteria'] = array();
//            }
//            $criteria      = $this->get('app.utils')->flatten_array($queryResults['criteria']);
//            $tt_exceptions = $queryResults['tt_exceptions'];
//            $url_source    = $queryResults['url_source'];
//
//            $this->addErrorLog("$url_source [$guid] ($criteria):: Encountered ".count($tt_exceptions).' exception'.(count($tt_exceptions) == 1 ? '' : 's'));
//
//            foreach ($tt_exceptions as $indx => $tt_exception) {
//                $traceURL = $tt_exception['traceURL'];
//                $this->addErrorLog("$traceURL [$guid]:: Exception {indx} type:: {ex_type} message:: {ex_messsage}", array('indx' => $indx, 'ex_type' => $tt_exception['type'], 'ex_message' => $tt_exception['exception']));
//            }
//        }
//    }

    public function allHotelsIn($parameters = array())
    {
        $request   = Request::createFromGlobals();
        $routepath = $this->getRoutePath($request);        
        $dest = (isset($parameters['dest']))?$parameters['dest']:'';
        $srch = (isset($parameters['srch']))?$parameters['srch']:'';
        $nearby = (isset($parameters['nearby']))?$parameters['nearby']:0;
        
        $poi_latitude    = $poi_longitude = $imageExists = $oldQuery = $sortGeolocation = NULL;
        $cityName        = $country_code = $country_codeiso = '';
        $seotitle        = $parameters['seotitle'];
        $seodescription  = $parameters['seodescription'];
        $seokeywords     = $parameters['seokeywords'];
        $dest            = $dest.''.$srch;
        $dest_str        = urldecode($dest);
        $dest_str        = str_replace('/', '', $dest_str);
        $destination_str = explode('-', $dest_str);
        $last_record     = count($destination_str) - 1;
        $hoteldest       = explode('_', $destination_str[$last_record]);
        $last_record2    = count($hoteldest) - 2;
        $maxPage         = $this->container->getParameter('MAX_RECORD');
        $pageType        = 1;
        if ($last_record2 < 0 && $nearby != 0) {
            return $this->pageNotFoundAction();
        }
        $this->data['pageType'] = $pageType;
        $poi_id                 = 0;
        if ($nearby != 0) {
            $sortGeolocation = 1;
            $poi_id  = $hoteldest[$last_record2];
            $poiInfo = $this->get('ReviewsServices')->getPoiInfo($poi_id);

            if (intval($poiInfo[0]->getCityId()) != 0) {
                $check = $this->get('ReviewsServices')->getCityDiscoverHotelsCount('', '', $poiInfo[0]->getCityId());
                if ($check > 1) {
                    $city            = $cityId          = $poiInfo[0]->getCityId();
                    $city_info       = $this->get('CitiesServices')->worldcitiespopInfo($city);
                    $cityName        = $this->get('app.utils')->htmlEntityDecode($city_info[0]->getName());
                    $country_code    = $city_info[0]->getCountryCode();
                    $country_codeiso = $city_info[1]->getIso3();
                }
            }
            $poi_latitude  = $poiInfo[0]->getLatitude();
            $poi_longitude = $poiInfo[0]->getLongitude();
        } else {
            $imageExists = $oldQuery = 1;
        }        

        if ($nearby == 0 && strtoupper($hoteldest[0]) != "CO" && strtoupper($hoteldest[0]) != "C" && strtoupper($hoteldest[0]) != "S" && strtoupper($hoteldest[0]) != "") {
            if (strrpos($destination_str[$last_record], 'C_')) $hoteldest[0] = 'C';
            else if (strrpos($destination_str[$last_record], 'CO_')) $hoteldest[0] = 'CO';
            else if (strrpos($destination_str[$last_record], 'S_')) $hoteldest[0] = 'S';
        }
        if (isset($hoteldest[2])) {
            $page = $hoteldest[count($hoteldest) - 1];
        } else {
            $page = 1;
        }
        $page = intval($page);
        if ($page < 0) $page = 1;

        if ($page <= 1) {
            $page = 1;
            $from = 0;
        } elseif ($page > 1) {
            $from = ($page - 1) * 10;
        } elseif ($page >= $maxPage) {
            $from = ($maxPage - 1) * 10;
        }
        if ($from > 9900) {
            return $this->pageNotFoundAction();
        }
        $c_id  = 0;
        $co_id = $s_id = $countrybreadcrumb = $countrybreadcrumblink = $citybreadcrumblink = $realnameseoTitle = $seo_name = $sortby = $link_name = "";

        if (strtoupper($hoteldest[0]) == "CO") {
            $type          = 3;
            $country_array = $this->get('CmsCountriesServices')->countryGetInfo($hoteldest[1]);
            if (sizeof($country_array) == 0) {
                return $this->pageNotFoundAction();
            }
            $link_name = $seo_name  = $this->get('app.utils')->htmlEntityDecode($country_array->getName());

            $realnameseoTitle = $seo_name;
            $realnameseoTitle = $this->get('app.utils')->getMultiByteSubstr($realnameseoTitle, 26, NULL, $this->data['LanguageGet'], false);

            $co_id = $hoteldest[1];
            $citybreadcrumblink = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $link_name, $c_id, $s_id, $co_id);

            $sortby            = 'media.images.id';            
            $url_source        = 'allHotelsIn - getHotelsInSearch(country) - URL: '.$routepath;            
        } else if (strtoupper($hoteldest[0]) == "S") {
            $type        = 2;
            $s_id        = $hoteldest[1];
            $co_id       = $hoteldest[2];
            $state_array = $this->get('CitiesServices')->worldStateInfo($co_id, $s_id);
            if ($state_array && sizeof($state_array)) {
                $link_name        = $seo_name         = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                $realnameseoTitle = $seo_name;
                $realnameseoTitle = $this->get('app.utils')->getMultiByteSubstr($realnameseoTitle, 22, NULL, $this->data['LanguageGet'], false);

                $realnameseoTitle  .= ' '.$state_array[1]->getIso3();
                if ($state_array) $seo_name          .= ' '.$state_array[1]->getIso3();
                $countrybreadcrumb = $this->get('app.utils')->htmlEntityDecode($state_array[1]->getName());

                $lknameco              = $this->get('app.utils')->cleanTitleData($countrybreadcrumb);
                $countrybreadcrumblink = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $lknameco, 0, '', $state_array[1]->getCode());
                $citybreadcrumblink    = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $link_name, 0, $s_id, $state_array[1]->getCode());
            } else {
                $link_name        = $seo_name         = $destination_str[1];
                $realnameseoTitle = $seo_name;
                $realnameseoTitle = $this->get('app.utils')->getMultiByteSubstr($realnameseoTitle, 26, NULL, $this->data['LanguageGet'], false);
            }

            $sortby = 'media.images.id';
            $url_source        = 'allHotelsIn - getHotelsInSearch(state) - URL: '.$routepath;
        } else if (strtoupper($hoteldest[0]) == "C") {
            $type      = 1;
            $c_id      = intval($hoteldest[1]);
            $city_info = $this->get('CitiesServices')->worldcitiespopInfo($c_id);
            $link_name = $seo_name  = $this->get('app.utils')->htmlEntityDecode($city_info[0]->getName());

            $countrybreadcrumb = $this->get('app.utils')->htmlEntityDecode($city_info[1]->getName());
            if ($city_info) {
                $state_array = $this->get('CitiesServices')->worldStateInfo($city_info[0]->getCountryCode(), $city_info[0]->getStateCode());
                if ($state_array && sizeof($state_array)) {
                    $seo_name .= ','.$this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                }
                $realnameseoTitle = $seo_name;
                $realnameseoTitle = $this->get('app.utils')->getMultiByteSubstr($realnameseoTitle, 22, NULL, $this->data['LanguageGet'], false);
                $realnameseoTitle .= ' '.$city_info[1]->getIso3();
                $seo_name         .= ' '.$city_info[1]->getIso3();
            } 

            $lknameco              = $this->get('app.utils')->cleanTitleData($countrybreadcrumb);
            $countrybreadcrumblink = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $lknameco, 0, '', $city_info[1]->getCode(), 1);
            $citybreadcrumblink    = $this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $link_name, $c_id);

            $sortby            = 'media.images.id';
            $url_source        = 'allHotelsIn - getHotelsInSearch(city) - URL: '.$routepath;
        } else if ($nearby != 0) {
            $sortby = 'vendor.coordinates';
            $link_name    = $seo_name     = $this->get('app.utils')->htmlEntityDecode($poiInfo[0]->getName());

            if ($page > 1) {
                return $this->redirect($this->get('TTRouteUtils')->returnHotelsNearByLink($this->data['LanguageGet'], $link_name, $poi_id, 1), 301);
            }
            
            $url_source        = 'allHotelsIn - getHotelsInSearch(nearby) - URL: '.$routepath;
            $co_id = $country_code;
            if ($poiInfo[0]->getCityId() != 0) 
            {
                $c_id = $poiInfo[0]->getCityId();
            }
        } else {
            return $this->pageNotFoundAction();
        }
        
        
        if ( $nearby == 0 && $page > 1 ) {
            return $this->redirect($this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $link_name, $c_id, $s_id, $co_id, 1), 301);
        }
        
        $options      = array
        (
            'nearby'      => $nearby,
            'limit'       => 10,
            'from'        => $from,
            'url_source'  => $url_source,
            'routepath'   => $routepath,
            'countryCode' => $co_id,
            'stateCode'   => $s_id,
            'cityId'      => $c_id,
            'sortBy'      => $sortby,
            'lang'        => $this->data['LanguageGet'],
            'imageExists' => $imageExists,
            'oldQuery'    => $oldQuery,
            'sortGeolocation' => $sortGeolocation,
            'sortByLat' => $poi_latitude,
            'sortByLon' => $poi_longitude
        );
        
        list( $hotels_array, $count, $country_name ) = $this->get('ElasticServices')->getHotelsInSearch( $options );
        
        if ( sizeof($hotels_array) <= 0 ) {
            return $this->pageNotFoundAction();
        }
        
        $seo_name_pagination = $seo_name;
        if ($nearby != 0) {
            $this->setHreflangLinks($this->get('TTRouteUtils')->returnHotelsNearByLink($this->data['LanguageGet'], $link_name, $poi_id, 1), true, true);
        } else {
            $this->setHreflangLinks($this->get('TTRouteUtils')->returnHotelsInLink($this->data['LanguageGet'], $link_name, $c_id, $s_id, $co_id, 1), true, true);
        }

        $descHotelsin      = $descHotelsinTitle = $hotelsinLink = '';
        if ($page == 1 && $c_id > 0 && $nearby == 0) {
            $options_arr    = array(
                'show_main' => 0,
                'limit' => 40,
                'lang' => $this->data['LanguageGet'],
                'city_id' => $c_id
            );
            $thingstodoList = $this->container->get('ThingsToDoServices')->getThingstodoList($options_arr);
            foreach ($thingstodoList as $thingstodoInfo) {
                if (isset($thingstodoInfo['t_descHotelsin']) && $thingstodoInfo['t_descHotelsin']) {
                    if ($thingstodoInfo['ml_descHotelsin'] != '') $descHotelsin      = $thingstodoInfo['ml_descHotelsin'];
                    else $descHotelsin      = $thingstodoInfo['t_descHotelsin'];
                    $descHotelsinTitle = $this->translator->trans('Hotels in').' '.$link_name;
                    break;
                }
            }
        }
        $this->data['descHotelsin']      = $descHotelsin;
        $this->data['descHotelsinTitle'] = $descHotelsinTitle;
        $this->data['hotelsinLink']      = $hotelsinLink;

        
        if ($this->data['aliasseo'] == '') {
            if ($nearby != 0) {
                $action_array     = array();
                $Tseo             = $this->get('app.utils')->htmlEntityDecode($poiInfo[0]->getName());
                $realnameseoTitle = $Tseo;
                $realnameseoTitle = $this->get('app.utils')->getMultiByteSubstr($realnameseoTitle, 18, NULL, $this->data['LanguageGet'], false);

                $Tseo = $this->get('app.utils')->getMultiByteSubstr($Tseo, 45, NULL, $this->data['LanguageGet'], false);

                if ($cityName != '') {
                    $Tseo             .= ' '.$cityName;
                    $Tseo             = $this->get('app.utils')->getMultiByteSubstr($Tseo, 66, NULL, $this->data['LanguageGet'], false);
                    $realnameseoTitle .= ' '.$cityName;
                }
                $realnameseoTitle = $this->get('app.utils')->getMultiByteSubstr($realnameseoTitle, 26, NULL, $this->data['LanguageGet'], false);

                if ($country_code != '') $realnameseoTitle       .= ' '.$country_code;
                if ($country_codeiso != '') $Tseo                   .= ' '.$country_codeiso;
                $action_array[]         = $realnameseoTitle.'-part '.$page;
                $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
                $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

                $action_array                 = array();
                $action_array[]               = $Tseo;
                $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
                $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

                $action_array              = array();
                $action_array[]            = $Tseo;
                $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
                $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            } else {
                $seo_name               .= ' part '.$page;
                $realnameseoTitle       .= '-part '.$page;
                $action_array           = array();
                $action_array[]         = $realnameseoTitle;
                $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
                $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

                $action_array                 = array();
                $action_array[]               = $seo_name;
                $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
                $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

                $action_array              = array();
                $action_array[]            = $seo_name;
                $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
                $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            }
        }

        $number_of_pages = $count / 10;
        $number_of_pages = ceil($number_of_pages);
        $search_paging_output = '';

        $hotel_prefrences = array();
        $hotel_property_type  = array();
        $hotel_property_types = array();
        $dest_s_id            = ( sizeof($hoteldest) > 2 ) ? $hoteldest[2] : '';
        if ($citybreadcrumblink != '') {
            $this->data['cityNamebreadcrumb'] = 'hotels in '.$destination_str[1];
        } else {
            $this->data['cityNamebreadcrumb'] = '';
        }
        $this->data['citybreadcrumblink'] = $citybreadcrumblink;
        if ($nearby != 0) {
            $this->data['totalpage'] = 100;
            $this->data['city']      = $cityName;
            $this->data['total']     = 100;
        } else {
            $this->data['totalpage'] = ceil($number_of_pages);
            $this->data['city']      = $destination_str[1];
            $this->data['total']     = $count;
        }
        $this->data['dest']                  = $dest;
        $this->data['type']                  = 1;
        $this->data['country']               = $country_name;
        $this->data['countrybreadcrumb']     = $countrybreadcrumb;
        $this->data['countrybreadcrumblink'] = $countrybreadcrumblink;
        $this->data['paging']                = $search_paging_output;
        $this->data['hotels_array']          = $hotels_array;
        $this->data['entity_type']           = $this->container->getParameter('SOCIAL_ENTITY_HOTEL');

        $this->data['ttmedia_link']           = '';
        $this->data['hotel_prefrences']       = $hotel_prefrences;
        $this->data['hotel_prefrencesKey']    = $this->translator->trans('Amenities');
        $this->data['hotel_property_type']    = $hotel_property_types;
        $this->data['hotel_property_typeKey'] = $this->translator->trans('Accomodation type');
        $this->data['restaurantsnearlink']    = '';
        $this->data['hotelsnearlink']         = '';
        $this->data['restaurantsnearname']    = '';
        $this->data['hotelsnearname']         = '';
        $this->data['hide_stars']             = 0;
        $this->data['hide_filter']            = 0;
        if ($nearby != 0) {
            $this->data['page_title'] = $this->translator->trans('Hotels near by').' '.$this->get('app.utils')->htmlEntityDecode($poiInfo[0]->getName());
        } else {
            $this->data['page_title'] = $this->translator->trans('Hotels in').' '.$this->data['city'];
        }
        return $this->render('default/hotels.in.twig', $this->data);
    }
    
    public function flightEmailToAdminAction()
    {
        return $this->render('emails/flight_email_to_admin.twig', $this->data);
    }

    public function invoiceReceiptForAdminAction()
    {
        return $this->render('default/invoice-receipt_for_admin.twig', $this->data);
    }

    public function hotelsNearByRedirectAction($dest, $dest1, $seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_hotels_near', array('dest' => $dest, 'dest1' => $dest1), 301);
    }

    public function hotelsNearByAction($dest, $dest1, $seotitle, $seodescription, $seokeywords)
    {
        $parameters = array(
            'dest' => $dest,
            'srch' => $dest1,
            'nearby' => 1,
            'seotitle' => $seotitle,
            'seodescription' => $seodescription,
            'seokeywords' => $seokeywords
        );

        return $this->allHotelsIn($parameters);
    }
}
