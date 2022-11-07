<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle\vendors\payfort\v2;

use PaymentBundle\Model\Payment;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\CurrencyService;

/**
 * Description of PayfortIntegration
 *
 * @author para-soft7
 */
class PayfortIntegration
{
    public $gatewayHost        = 'https://checkout.payfort.com/';
    public $gatewaySandboxHost = 'https://sbcheckout.payfort.com/';
    public $language           = 'en';

    /**
     * @var boolean for live account change it to false
     */
    public $sandboxMode = true;

    /**
     * @var string your Merchant Identifier account (mid)
     */
    public $merchantIdentifier = 'qvnFNPMX';

    /**
     * @var string your access code
     */
    public $accessCode = 'lXJ9rz4VF2VVk7pRrRJy';

    /**
     * @var string SHA Request passphrase
     */
    public $SHARequestPhrase = 'sdetgsegtest';

    /**
     * @var string SHA Response passphrase
     */
    public $SHAResponsePhrase = 'sgtsetgswtaet';

    /**
     * @var string SHA Type (Hash Algorith)
     * expected Values ("sha1", "sha256", "sha512")
     */
    public $SHAType = 'sha256';

    /**
     * @var string  command
     * expected Values ("AUTHORIZATION", "PURCHASE")
     */
    public $command = '';

    /**
     * @var decimal order amount
     */
    public $amount = '';

    /**
     * @var string order currency
     */
    public $currency = '';

    /**
     * @var string item name
     */
    public $moduleName = '';

    /**
     * @var string you can change it to your email
     */
    public $customerEmail = '';

    /**
     * @var string  project root folder
     * change it if the project is not on root folder.
     */
    public $projectUrlPath = '';

    /**
     *
     * @var type varchar 9 unique code by transaction created
     */
    public $uuid = '';

    /**
     * @var sting customer full name, other then the cardholder name
     */
    public $customerFullName   = '';
    public $merchantReference  = '';
    private $production_server = false;

    public function __construct(Payment $payment)
    {

//     $this->production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
//
//       if ($this->production_server) {
//
//            $this->TEST_MODE = false;
//            $this->ONLINE    = true;
//            $this->sandboxMode = false;
//            $this->merchantIdentifier = 'PnuSSuFh';
//            $this->accessCode = 'AQZTIksoaE31zIPUXI9F';
//            $this->SHARequestPhrase = 'd9c8e8b8fc4eabe';
//            $this->SHAResponsePhrase = 'b8449123f551e9d';
//
//       }

        $this->setAmount($payment->getAmount());
        $this->setCurrency($payment->getCurrency());
        $this->setCommand($payment->getCommand());
        $this->setModuleName($payment->getModuleName());
        $this->setCustomerEmail($payment->getCustomerEmail());
        $this->setCustomerFullName($payment->getCustomerFullName());
        $this->setMerchantReference($payment->getMerchantReference());
        $this->setUuid($payment->getNumber());
    }

    //Command setter
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    //Command Getter ( maybe will not be used )
    public function getUuid()
    {
        return $this->uuid;
    }

    //Command setter
    public function setCommand($command)
    {
        $this->command = $command;
    }

    //Command Getter ( maybe will not be used )
    public function getCommand()
    {
        return $this->command;
    }

    //amount setter
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    //amount Getter ( maybe will not be used )
    public function getAmount()
    {
        return $this->amount;
    }

    //currency setter
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    //currency Getter ( maybe will not be used )
    public function getCurrency()
    {
        return $this->currency;
    }

    //Setter of the type of the purchase ( flight, hotel, packages )
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    // type of the purchase Getter ( maybe will not be used )
    public function getModuleName()
    {
        return $this->moduleName;
    }

    //Setting the email of the customer
    public function setCustomerEmail($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    //Getting the email of the customer
    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    //Setting the cusotmer full name of the customer
    public function setCustomerFullName($customerFullName)
    {
        $this->customerFullName = $customerFullName;
    }

    //Getting the customer full name of the customer
    public function getCustomerFullName()
    {
        return $this->customerFullName;
    }

    //Setting the Merchant Reference of the trx
    public function setMerchantReference($merchantReference)
    {
        $this->merchantReference = $merchantReference;
    }

    //Getting the Merchant Reference of the trx
    public function getMerchantReference()
    {
        return $this->merchantReference;
    }

    public function processRequest($paymentMethod, $uuid)
    {

        if ($paymentMethod == 'cc_merchantpage' || $paymentMethod == 'cc_merchantpage2') {

            $merchantPageData = $this->getMerchantPageData($paymentMethod, $uuid);
            $postData         = $merchantPageData['params'];
            $gatewayUrl       = $merchantPageData['url'];
        } else {

            $data       = $this->getRedirectionData($paymentMethod, $uuid);
            $postData   = $data['params'];
            $gatewayUrl = $data['url'];
        }
        $form = $this->getPaymentForm($gatewayUrl, $postData);
        echo json_encode(array('form' => $form, 'url' => $gatewayUrl, 'params' => $postData, 'paymentMethod' => $paymentMethod));
        exit;
    }

    public function getRedirectionData($paymentMethod, $uuid)
    {
        $merchantReference = $this->generateMerchantReference($uuid);
        if ($this->sandboxMode) {
            $gatewayUrl = $this->gatewaySandboxHost.'FortAPI/paymentPage';
        } else {
            $gatewayUrl = $this->gatewayHost.'FortAPI/paymentPage';
        }

        if ($paymentMethod == 'sadad') {
            $this->currency = 'SAR';
        }
        $postData = array(
            'amount' => $this->convertFortAmount($this->getAmount(), $this->getCurrency()),
            'currency' => strtoupper($this->getCurrency()),
            'merchant_identifier' => $this->merchantIdentifier,
            'access_code' => $this->accessCode,
            'merchant_reference' => $merchantReference,
            'customer_email' => $this->getCustomerEmail(),
            //'customer_name'         => trim($order_info['b_firstname'].' '.$order_info['b_lastname']),
            'command' => $this->getCommand(),
            'language' => $this->language,
            'return_url' => $this->getUrl('payment-processReques?transaction_id='.$uuid),
        );

        if ($paymentMethod == 'sadad') {
            $postData['payment_option'] = 'SADAD';
        } elseif ($paymentMethod == 'naps') {
            $postData['payment_option']    = 'NAPS';
            $postData['order_description'] = $this->moduleName;
        } elseif ($paymentMethod == 'installments') {
            $postData['installments'] = 'STANDALONE';
            $postData['command']      = 'PURCHASE';
        }
        $postData['signature'] = $this->calculateSignature($postData, 'request');
        $debugMsg              = "Fort Redirect Request Parameters \n".print_r($postData, 1);
        $this->log($debugMsg);
        return array('url' => $gatewayUrl, 'params' => $postData);
    }

    public function getMerchantPageData($paymentMethod, $uuid)
    {
        $merchantReference = $this->generateMerchantReference($uuid);
        $returnUrl         = $this->getUrl('payment-MerchantPage?transaction_id='.$uuid);
        if (isset($_GET['3ds']) && $_GET['3ds'] == 'no') {
            $returnUrl = $this->getUrl('payment-MerchantPage?transaction_id='.$uuid.'3ds=no');
        }
        $iframeParams              = array(
            'merchant_identifier' => $this->merchantIdentifier,
            'access_code' => $this->accessCode,
            'merchant_reference' => $merchantReference,
            'service_command' => 'TOKENIZATION',
            'language' => $this->language,
            'return_url' => $returnUrl,
        );
        $iframeParams['signature'] = $this->calculateSignature($iframeParams, 'request');

        if ($this->sandboxMode) {
            $gatewayUrl = $this->gatewaySandboxHost.'FortAPI/paymentPage';
        } else {
            $gatewayUrl = $this->gatewayHost.'FortAPI/paymentPage';
        }
        $debugMsg = "Fort Merchant Page Request Parameters \n".print_r($iframeParams, 1);
        $this->log($debugMsg);

        return array('url' => $gatewayUrl, 'params' => $iframeParams);
    }

    public function getPaymentForm($gatewayUrl, $postData)
    {
        $form = '<form style="display:none" name="payfort_payment_form" id="payfort_payment_form" method="post" action="'.$gatewayUrl.'">';
        foreach ($postData as $k => $v) {
            $form .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
        }
        $form .= '<input type="submit" id="submit">';
        return $form;
    }

    public function processResponse($uuid, $jsonResponse)
    {
        $fortParams = $jsonResponse; //array_merge($_GET, $_POST);

        $debugMsg = "Fort Redirect Response Parameters \n".print_r($fortParams, 1);
        $this->log($debugMsg);

        $reason        = '';
        $response_code = '';
        $success       = true;
        if (empty($fortParams)) {
            $success  = false;
            $reason   = "Invalid Response Parameters";
            $debugMsg = $reason;
            $this->log($debugMsg);
        } else {
            //validate payfort response
            $params              = $fortParams;
            $responseSignature   = $fortParams['signature'];
            $merchantReference   = $params['merchant_reference'];
            unset($params['r']);
            unset($params['signature']);
            unset($params['integration_type']);
            unset($params['transaction_id']);
            $calculatedSignature = $this->calculateSignature($params, 'response');
            $success             = true;
            $reason              = '';

            if ($responseSignature != $calculatedSignature) {
                $success  = false;
                $reason   = 'Invalid signature 1';
                $debugMsg = sprintf('Invalid Signature.1 Calculated Signature: %1s, Response Signature: %2s', $responseSignature, $calculatedSignature);
                $this->log($debugMsg);
            } else {
                $response_code    = $params['response_code'];
                $response_message = $params['response_message'];
                $status           = $params['status'];
                if (substr($response_code, 2) != '000') {
                    $success  = false;
                    $reason   = $response_message;
                    $debugMsg = $reason;
                    $this->log($debugMsg);
                }
            }
        }
        //
        $p          = $fortParams;
        $p['msg']   = $reason;
        $p          = http_build_query($p);
        $return_url = null;
        //
        if (!$success) {
            $return_url = $this->getUrl('error?'.$p);
        } else {
            $return_url = $this->getUrl('success?'.$p);
        }
        //
        return array("success" => false, "return_url" => $return_url);
        //
//        echo "<html><body onLoad=\"javascript: window.top.location.href='".$return_url."'\"></body></html>";
//        exit;
    }

    public function processMerchantPageResponse($uuid, $tokenResultJson)
    {
        $fortParams = $tokenResultJson; //array_merge($_GET, $_POST);

        $debugMsg      = "Fort Merchant Page Response Parameters \n".print_r($fortParams, 1);
        $this->log($debugMsg);
        $reason        = '';
        $response_code = '';
        $success       = true;
        if (empty($fortParams)) {
            $success  = false;
            $reason   = "Invalid Response Parameters";
            $debugMsg = $reason;
            $this->log($debugMsg);
        } else {
            //validate payfort response
            $params              = $fortParams;
            $responseSignature   = $fortParams['signature'];
            unset($params['r']);
            unset($params['signature']);
            unset($params['integration_type']);
            unset($params['3ds']);
            unset($params['transaction_id']);
            $merchantReference   = $params['merchant_reference'];
            $calculatedSignature = $this->calculateSignature($params, 'response');
            $success             = true;
            $reason              = '';

            if ($responseSignature != $calculatedSignature) {
                $success  = false;
                $reason   = 'Invalid signature. 2';
                $debugMsg = sprintf('Invalid Signature 2. Calculated Signature: %1s, Response Signature: %2s', $responseSignature, $calculatedSignature);
                $this->log($debugMsg);
            } else {
                $response_code    = $params['response_code'];
                $response_message = $params['response_message'];
                $status           = $params['status'];
                if (substr($response_code, 2) != '000') {
                    $success  = false;
                    $reason   = $response_message;
                    $debugMsg = $reason;
                    $this->log($debugMsg);
                } else {
                    $success         = true;
                    $host2HostParams = $this->merchantPageNotifyFort($fortParams, $uuid);
                    $debugMsg        = "Fort Merchant Page Host2Hots Response Parameters \n".print_r($fortParams, 1);
                    $this->log($debugMsg);
                    if (!$host2HostParams) {
                        $success  = false;
                        $reason   = 'Invalid response parameters.';
                        $debugMsg = $reason;
                        $this->log($debugMsg);
                    } else {
                        $params              = $host2HostParams;
                        $responseSignature   = $host2HostParams['signature'];
                        $merchantReference   = $params['merchant_reference'];
                        unset($params['r']);
                        unset($params['signature']);
                        unset($params['integration_type']);
                        unset($params['transaction_id']);
                        $calculatedSignature = $this->calculateSignature($params, 'response');
                        if ($responseSignature != $calculatedSignature) {
                            $success  = false;
                            $reason   = 'Invalid signature. 3';
                            $debugMsg = sprintf('Invalid Signature. 3 Calculated Signature: %1s, Response Signature: %2s', $responseSignature, $calculatedSignature);
                            $this->log($debugMsg);
                        } else {
                            $response_code    = $params['response_code'];
                            $response_message = $params['response_message'];
                            $status           = $params['status'];
                            if ($response_code == '20064' && isset($params['3ds_url'])) {
                                $success                       = true;
                                $debugMsg                      = 'Redirect to 3DS URL : '.$params['3ds_url'];
                                $this->log($debugMsg);
                                if (!isset($host2HostParams['success'])) $host2HostParams['success']    = true;
                                if (!isset($host2HostParams['return_url'])) $host2HostParams['return_url'] = $host2HostParams['3ds_url'];
                                $host2HostParams['command']    = $this->getCommand();
                                return $host2HostParams; //array("success" => true, "return_url" => $params['3ds_url']);
                                //echo "<html><body onLoad=\"javascript: window.top.location.href='" . $params['3ds_url'] . "'\"></body></html>";
                                //exit;
//                                header('location:'.$params['3ds_url']);
//                                $data = array();
//
//                                $url = $params['3ds_url'];
//
//                                echo  $this->sendAsNormalPostRequestCurl($data, $url, 'form', "text", true);
//                                $ch         = curl_init($url);
//                                # Form data string
//                                $postString = http_build_query($data, '', '&');
//                                # Setting our options
//                                curl_setopt($ch, CURLOPT_POST, true);
//                                curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
//                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//                                # Get the response
//                                $response   = curl_exec($ch);
//                                curl_close($ch);
//
//                                print_r($response);
                            } else {
                                if (substr($response_code, 2) != '000') {
                                    $success  = false;
                                    $reason   = $host2HostParams['response_message'];
                                    $debugMsg = $reason;
                                    $this->log($debugMsg);
                                }
                            }
                        }
                    }
                }
            }
            //
            //
            $params['message'] = $reason;
            $return_url        = "_payment_processRequest"; //$this->getUrl('error?'.http_build_query($params));
            //
            return array("success" => $success, "data" => $fortParams, "return_url" => $return_url, "command" => $this->getCommand(),
                'message' => $reason, 'response_code' => $response_code, 'response_message' => $response_message, 'status' => $status,
                'merchant_reference' => $merchantReference);

//            echo "<html><body onLoad=\"javascript: window.top.location.href='".$return_url."'\"></body></html>";
//            exit;
        }
    }

    public function merchantPageNotifyFort($fortParams, $uuid)
    {
        //send host to host
        if ($this->sandboxMode) {
            $gatewayUrl = $this->gatewaySandboxHost.'FortAPI/paymentPage';
        } else {
            $gatewayUrl = $this->gatewayHost.'FortAPI/paymentPage';
        }

        $postData = array(
            'merchant_reference' => $fortParams['merchant_reference'],
            'access_code' => $this->accessCode,
            'command' => $this->getCommand(),
            'merchant_identifier' => $this->merchantIdentifier,
            'customer_ip' => $_SERVER['REMOTE_ADDR'],
            'amount' => $this->convertFortAmount($this->getAmount(), $this->getCurrency()),
            'currency' => strtoupper($this->getCurrency()),
            'customer_email' => $this->getCustomerEmail(),
            'customer_name' => $this->getCustomerFullName(),
            'token_name' => $fortParams['token_name'],
            'language' => $this->language,
            'return_url' => $this->getUrl('payment-processRequest?transaction_id='.$uuid),
        );
        if (isset($fortParams['3ds']) && $fortParams['3ds'] == 'no') {
            $postData['check_3ds'] = 'NO';
        }

        //calculate request signature
        $signature             = $this->calculateSignature($postData, 'request');
        $postData['signature'] = $signature;

        $debugMsg = "Fort Host2Host Request Parameters \n".print_r($postData, 1);
        $this->log($debugMsg);

        if ($this->sandboxMode) {
            $gatewayUrl = 'https://sbpaymentservices.PayFort.com/FortAPI/paymentApi';
        } else {
            $gatewayUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
        }


        $json_result = $this->sendAsNormalPostRequestCurl($postData, $gatewayUrl, 'json', "json", true); //$this->callApi($postData, $gatewayUrl);

        $debugMsg = "Fort Host2Host Response Parameters \n".print_r($json_result, 1);
        $this->log($debugMsg);

        return $json_result;
    }

    /**
     * Send host to host request to the Fort
     * @param array $postData
     * @param string $gatewayUrl
     * @return mixed
     */
    public function callApi($postData, $gatewayUrl)
    {
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        $useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0";
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            //      'multipart/form-data'
//    'application/x-www-form-urlencoded'
            'Content-Type: application/json;charset=UTF-8',
            //'Accept: application/json, application/*+json',
            //'Connection:keep-alive'
        ));
        curl_setopt($ch, CURLOPT_URL, $gatewayUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_ENCODING, "compress, gzip");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // allow redirects
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); // The number of seconds to wait while trying to connect
        //curl_setopt($ch, CURLOPT_TIMEOUT, Yii::app()->params['apiCallTimeout']); // timeout in seconds
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);

        //$response_data = array();
        //parse_str($response, $response_data);
        curl_close($ch);

        $array_result = json_decode($response, true);

        if (!$response || empty($array_result)) {
            return false;
        }
        return $array_result;
    }

    /**
     * calculate fort signature
     * @param array $arrData
     * @param string $signType request or response
     * @return string fort signature
     */
    public function calculateSignature($arrData, $signType = 'request')
    {
        $shaString = '';

        ksort($arrData);
        foreach ($arrData as $k => $v) {
            $shaString .= "$k=$v";
        }

        if ($signType == 'request') {
            $shaString = $this->SHARequestPhrase.$shaString.$this->SHARequestPhrase;
        } else {
            $shaString = $this->SHAResponsePhrase.$shaString.$this->SHAResponsePhrase;
        }
        $signature = hash($this->SHAType, $shaString);

        return $signature;
    }

    /**
     * Convert Amount with dicemal points
     * @param decimal $amount
     * @param string  $currencyCode
     * @return decimal
     */
    public function convertFortAmount($amount, $currencyCode)
    {

        $new_amount    = 0;
        $total         = $amount;
        $decimalPoints = $this->getCurrencyDecimalPoints($currencyCode);
        $new_amount    = round($total, $decimalPoints) * (pow(10, $decimalPoints));
        return $new_amount;
    }

    public function castAmountFromFort($amount, $currencyCode)
    {
        $decimalPoints = $this->getCurrencyDecimalPoints($currencyCode);
        //return $amount / (pow(10, $decimalPoints));
        $new_amount    = round($amount, $decimalPoints) / (pow(10, $decimalPoints));
        return $new_amount;
    }

    /**
     *
     * @param string $currency
     * @param integer
     */
    public function getCurrencyDecimalPoints($currency)
    {
        $decimalPoint  = 2;
        $arrCurrencies = array(
            'JOD' => 3,
            'KWD' => 3,
            'OMR' => 3,
            'TND' => 3,
            'BHD' => 3,
            'LYD' => 3,
            'IQD' => 3,
        );
        if (isset($arrCurrencies[$currency])) {
            $decimalPoint = $arrCurrencies[$currency];
        }
        return $decimalPoint;
    }

    public function getUrl($path)
    {
        //$url = 'http://'.$_SERVER['HTTP_HOST'].$this->projectUrlPath.'/'.$path;
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
        $url      = $protocol.$_SERVER['HTTP_HOST'].$this->projectUrlPath.'/'.$path;
        return $url;
    }

    public function generateMerchantReference($uuid)
    {
        return uniqid(substr($uuid, 5, 7));
    }

    /**
     * Log the error on the disk
     */
    public function log($messages)
    {

        $messages = "========================================================\n\n".$messages."\n\n";
        //
        $dir      = __DIR__.'/../../../../../app/logs';
        $file     = $dir.'/payment.log';

        if (file_exists($dir) && is_writable($dir)) {
            if (is_writable($file) && filesize($file) > 907200) {
                $fp = fopen($file, "r+");
                ftruncate($fp, 0);
                fclose($fp);
            }

            if (!file_exists($file) || is_writable($file)) {
                $myfile = fopen($file, "a+");
                fwrite($myfile, $messages);
                fclose($myfile);
            }
        }
    }

    /**
     *
     * @param type $po payment option
     * @return string payment option name
     */
    function getPaymentOptionName($po)
    {
        switch ($po) {
            case 'creditcard' : return 'Credit Cards';
            case 'cc_merchantpage' : return 'Credit Cards (Merchant Page)';
            case 'installments' : return 'Installments';
            case 'sadad' : return 'SADAD';
            case 'naps' : return 'NAPS';
            default : return '';
        }
    }

    public function tokenize($ccInfo, $uuid)
    {
        $merchantReference = $this->generateMerchantReference($uuid);
        $return_url        = $this->getUrl('payment-MerchantPage?transaction_id='.$uuid);
        $data              = [
            'merchant_identifier' => $this->merchantIdentifier,
            'access_code' => $this->accessCode,
            'merchant_reference' => $merchantReference,
            'service_command' => 'TOKENIZATION',
            'language' => $this->language,
            'return_url' => $return_url,
        ];

        $data['signature'] = urlencode($this->calculateSignature($data, 'request'));

        if ($this->sandboxMode) {
            $gatewayUrl = $this->gatewaySandboxHost.'FortAPI/paymentPage';
        } else {
            $gatewayUrl = $this->gatewayHost.'FortAPI/paymentPage';
        }

        $postData = $data + [
            'card_number' => $ccInfo["cardnumber"],
            'expiry_date' => $ccInfo["expiry_year"].$ccInfo["expiry_month"],
            'card_holder_name' => $ccInfo["card_holder_name"],
            'card_security_code' => $ccInfo["cvv"]
        ];

        return $this->sendAsNormalPostRequestCurl($postData, $gatewayUrl, 'form', "json", true);
    }

    protected function sendAsNormalPostRequest(array $data, $gatewayUrl)
    {
        echo "<html><head></head><body> <form method='POST' action='{$gatewayUrl}' id='authorize-frm'>";
        foreach ($data as $key => $value) {
            echo "<input type='hidden' name='{$key}' value='{$value}' />";
        }

        echo "</form><script>function submitForm(){document.getElementById('authorize-frm').submit();}submitForm();</script></body></html>";
        exit;
    }

    public function capture()
    {
        // $merchantReference = '03C-DF95a56e12331160';
        // $return_url        = $this->getUrl('payment-MerchantPage?transaction_id='.$uuid);
        $arrData = [
            'command' => 'CAPTURE',
            'access_code' => $this->accessCode,
            'merchant_identifier' => $this->merchantIdentifier,
            'amount' => $this->convertFortAmount($this->getAmount(), $this->getCurrency()),
            'currency' => strtoupper($this->getCurrency()),
            'language' => $this->language,
            'merchant_reference' => $this->getMerchantReference(),
        ];

        $arrData['signature'] = urlencode($this->calculateSignature($arrData, 'request'));

        if ($this->sandboxMode) {
            $gatewayUrl = 'https://sbpaymentservices.PayFort.com/FortAPI/paymentApi';
        } else {
            $gatewayUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
        }

        $capture = $this->callApi($arrData, $gatewayUrl);


        return $capture;


        //return $refundCapture;
    }

    public function refund()
    {
        // $merchantReference = '03C-DF95a56e12331160';
        // $return_url        = $this->getUrl('payment-MerchantPage?transaction_id='.$uuid);
        $arrData = [
            'command' => 'REFUND',
            'access_code' => $this->accessCode,
            'merchant_identifier' => $this->merchantIdentifier,
            'amount' => $this->convertFortAmount($this->getAmount(), $this->getCurrency()),
            'currency' => strtoupper($this->getCurrency()),
            'language' => $this->language,
            'merchant_reference' => $this->getMerchantReference(),
        ];

        $arrData['signature'] = urlencode($this->calculateSignature($arrData, 'request'));

        if ($this->sandboxMode) {
            $gatewayUrl = 'https://sbpaymentservices.PayFort.com/FortAPI/paymentApi';
        } else {
            $gatewayUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
        }

        $refund = $this->callApi($arrData, $gatewayUrl);


        return $refund;


        //return $refundCapture;
    }

    public function VoidOnHold()
    {
        // $merchantReference = '03C-DF95a56e12331160';
        // $return_url        = $this->getUrl('payment-MerchantPage?transaction_id='.$uuid);
        $arrData = [
            'command' => 'VOID_AUTHORIZATION',
            'access_code' => $this->accessCode,
            'merchant_identifier' => $this->merchantIdentifier,
            'language' => $this->language,
            'merchant_reference' => $this->getMerchantReference(),
        ];

        $arrData['signature'] = urlencode($this->calculateSignature($arrData, 'request'));

        if ($this->sandboxMode) {
            $gatewayUrl = 'https://sbpaymentservices.PayFort.com/FortAPI/paymentApi';
        } else {
            $gatewayUrl = 'https://paymentservices.payfort.com/FortAPI/paymentApi';
        }

        $voidOnHold = $this->callApi($arrData, $gatewayUrl);


        return $voidOnHold;


        //return $refundCapture;
    }

    protected function sendAsNormalPostRequestCurl($postData, $gatewayUrl, $contentType = 'json', $responseType = 'json', $returnTransfer = true)
    {
        //   var_dump($postData);
        //open connection
        $ch                      = curl_init();
        //
        //'multipart/form-data'
        //'application/x-www-form-urlencoded'
        //'Content-Type: application/json;charset=UTF-8',
        //'Accept: application/json, application/*+json',
        $HEADER_TYPE['json']     = 'Content-Type: application/json;charset=UTF-8';
        $HEADER_TYPE['form']     = 'multipart/form-data';
        $HEADER_TYPE['form-url'] = 'application/x-www-form-urlencoded';
        //

        $fields = "";
        if ($contentType == 'form') {
            foreach ($postData as $key => $value) {
                $fields .= $key.'='.$value.'&';
            }
            $fields = rtrim($fields, '&');
        } else $fields = json_encode($postData);

        //set the url, number of POST vars, POST data
        $useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0";
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $HEADER_TYPE[$contentType]
            //'Connection:keep-alive'
        ));
        curl_setopt($ch, CURLOPT_URL, $gatewayUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "compress, gzip");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer); // return into a variable
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); // The number of seconds to wait while trying to connect
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);

        // Check for errors and log the error message
        $errno = curl_errno($ch);
        if ($errno) {
            $error_message = curl_strerror($errno);
            $debugMsg      = "------------------------CURL-URL------------------------------\n {$gatewayUrl} \n";
            $debugMsg      .= "------------------------CURL-REQ-FIELDS-----------------------\n".print_r($fields, 1);
            $debugMsg      .= "------------------------CURL-RESP-----------------------------\n".print_r($response, 1);
            $debugMsg      .= "------------------------CURL-ERROR----------------------------\n ({$errno}):\n {$error_message} \n";
            $this->log($debugMsg);
        }
        curl_close($ch);
        // echo "<pre>$response</pre>";
        $array_result = $response;
        if ($responseType == "json") $array_result = json_decode($response, true);

        if (!$response || empty($array_result)) {
            return false;
        }
        return $array_result;
    }
}
?>
