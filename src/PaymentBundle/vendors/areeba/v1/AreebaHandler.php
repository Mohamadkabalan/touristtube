<?php

namespace PaymentBundle\vendors\areeba\v1;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;

class AreebaHandler implements \PaymentBundle\Services\PaymentServiceProvider
{
    protected $container;
    protected $arrCredential;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->gatewayHost = $container->getParameter('modules')['payment']['vendors']['areeba']['gatewayHost'];

        $this->arrCredential = array(
            'merchant' => $container->getParameter('modules')['payment']['vendors']['areeba']['merchant_id'],
            'apiUsername' => $container->getParameter('modules')['payment']['vendors']['areeba']['apiUsername'],
            'apiPassword' => $container->getParameter('modules')['payment']['vendors']['areeba']['apiPassword'],
        );
    }

    public function generateTransactionId($uuid)
    {
        return uniqid(substr($uuid, 5, 7));
    }

    public function createCheckoutSession($paymentInfo)
    {
        $request                          = $this->arrCredential;
        $request['apiOperation']          = 'CREATE_CHECKOUT_SESSION';
        $request['interaction.operation'] = 'PURCHASE';
        $request['order.id']              = $paymentInfo->getNumber();
        $request['order.amount']          = $paymentInfo->getAmount();
        $request['order.currency']        = $paymentInfo->getCurrency();

        $array_result                         = $this->sendCurlRequest($this->gatewayHost, $request);
        $array_result['transactionReference'] = $this->generateTransactionId($paymentInfo->getNumber());

        return $array_result;
    }

    public function verifyPaymentTransaction($request)
    {
        $genericCommands     = new ResponseStatusIdentifier();
        $areebaResponseCodes = new AreebaResponseCodes();

        $response = array();

        $order = $this->retrieveOrder($request['orderId']);

        $genericResponseCode = $areebaResponseCodes->getGenericResponseStatus($order['status']);

        $response['transaction_id']     = $request['orderId'];
        $response['response_code']      = $genericResponseCode;
        $response['response_message']   = $order['latestTransaction.result'];
        $response['merchant_reference'] = $order['latestTransaction.transaction.receipt'];
        $response['command']            = $genericCommands->getGenericCommand($areebaResponseCodes->getGenericCommandCode($order['latestTransaction.transaction.type']));
        $response['status']             = $genericResponseCode;

        if (ResponseStatusIdentifier::isSuccess($genericResponseCode)) {
            $response['success'] = "true";
        } elseif (ResponseStatusIdentifier::isPending($genericResponseCode)) {
            $response['success'] = "false";
        } elseif (ResponseStatusIdentifier::isFailure($genericResponseCode)) {
            $response['success'] = "false";
        }

        return $response;
    }

    public function getLatestPaymentTransaction($orderId)
    {
        return $this->retrieveOrder($orderId);
    }

    private function retrieveOrder($orderId)
    {
        $request                 = $this->arrCredential;
        $request['apiOperation'] = 'RETRIEVE_ORDER';
        $request['order.id']     = $orderId;

        $array_result = $this->sendCurlRequest($this->gatewayHost, $request);

        // capture the n from transaction[n] for the purpose of finding the last transaction info
        if (preg_match_all("/^transaction\[(\d+)\]\./m", implode("\n", array_keys($array_result)), $transactionKeys) !== false) {

            if (!empty($transactionKeys[1])) {
                $latestTransactionKey = max($transactionKeys[1]);

                $prefix = 'transaction['.$latestTransactionKey.']';

                $tmp_array = array();

                foreach ($array_result as $key => $value) {

                    // add transaction's latest info to tmp_array, exclude indices not pertaining to the latest transaction key
                    // The transaction's latest info are added under key prefix latestTransaction (instead of prefix transaction[n_max])
                    if (strpos($key, $prefix) === 0) {

                        $newKey = str_replace($prefix, 'latestTransaction', $key);

                        $tmp_array[$newKey] = $value;
                    } else if (strpos($key, 'transaction[') === false) { // add all non-transaction related info to tmp_array
                        $tmp_array[$key] = $value;
                    }
                }

                $array_result = $tmp_array;
            }
        }

        return $array_result;
    }

    public function tokenise($param, $uuid, $paymentInfo)
    {
        $sessionId = $param['session_id'];

        $request                       = $this->arrCredential;
        $request['apiOperation']       = 'TOKENIZE';
        $request['session.id']         = $sessionId;
        $request['sourceOfFunds.type'] = 'CARD';

        $array_result = $this->sendCurlRequest($this->gatewayHost, $request);

        return $array_result;
    }

    public function processPayment($trxId, $paymentInfo, $resultJson)
    {

    }

    public function voidOnHoldPayment($trxId)
    {

    }

    public function captureOnHoldPayment($trxId)
    {

    }

    public function refund($paymentInfo)
    {
        $genericCommands     = new ResponseStatusIdentifier();
        $areebaResponseCodes = new AreebaResponseCodes();

        $response                  = array();
        $response["data"]["trxId"] = $paymentInfo->getNumber();

        $order = $this->retrieveOrder($paymentInfo->getNumber());

        if ($order['totalRefundedAmount'] < $order['totalCapturedAmount']) {
            $transactionId  = $this->generateTransactionId($paymentInfo->getNumber());
            $refundResponse = $this->refundTransaction($paymentInfo->getNumber(), $transactionId, $paymentInfo->getAmount(), $paymentInfo->getCurrency());

            if ($refundResponse['success']) {
                $genericResponseCode                  = $areebaResponseCodes->getGenericResponseStatus('REFUNDED');
                $response["data"]["response_message"] = $refundResponse['result'];
            } else {
                $genericResponseCode                  = $areebaResponseCodes->getGenericResponseStatus('REFUND_FAILED');
                $response["data"]["response_message"] = $refundResponse['error'];
            }

            $response['success']               = $refundResponse['success'];
            $response["data"]["command"]       = $genericCommands->getGenericCommand($areebaResponseCodes->getGenericCommandCode($refundResponse['transaction.type']));
            $response["data"]["status"]        = $genericResponseCode;
            $response["data"]["response_code"] = $genericResponseCode;
        }
//        else {
//            $response["command"]          = $paymentInfo->getCommand();
//            $response["response_code"]    = $genericResponseCode;
//            $response["response_message"] = $refundResponse['error'];
//        }

        return $response;
    }

    public function refundTransaction($orderId, $transactionId, $amount, $currency)
    {
        $request                         = $this->arrCredential;
        $request['apiOperation']         = 'REFUND';
        $request['order.id']             = $orderId;
        $request['transaction.id']       = $transactionId;
        $request['transaction.amount']   = $amount;
        $request['transaction.currency'] = $currency;

        $array_result = $this->sendCurlRequest($this->gatewayHost, $request);

        return $array_result;
    }

    public function processResponse($trxId, $paymentInfo, $jsonResponse)
    {

    }

    /**
     * Send host to host request to Areeba
     * @param string $url
     * @param array $parameters
     * @return mixed
     */
    public function sendCurlRequest($url, $parameters)
    {
        $request = "";
        foreach ($parameters as $key => $value) {
            $request .= $key.'='.$value.'&';
        }
        $request = rtrim($request, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        // Check for errors
        $errno         = curl_errno($ch);
        $error_message = '';
        if ($errno) {
            $error_message = curl_strerror($errno);
        }
        $this->addLog($parameters, $url, $request, $response, $errno, $error_message);

        curl_close($ch);

        if (!$response) return array();

        $responseArray = array();
        foreach (explode("&", $response) as $pair) {
            $param                               = explode("=", $pair);
            $responseArray[urldecode($param[0])] = urldecode($param[1]);
        }

        if (!$responseArray) return array();

        $responseArray['success'] = ($responseArray['result'] == 'SUCCESS');

        if (!$responseArray['success']) {
            $responseArray['error'] = "ERROR: {$responseArray['error.cause']} - {$responseArray['error.explanation']}";
        }

        return $responseArray;
    }

    // Logging
    /**
     * This method adds a log
     *
     * @param string $logFile The log file's name
     * @param array $$parameters
     * @param string $url
     * @param string $request
     * @param string $response
     * @param string $errno
     * @param string $error_message
     *
     * @return
     */
    private function addLog($parameters, $url, $request, $response, $errno, $error_message)
    {
        $parameters['timeStart'] = date('Ymd', time());

        $logFile = $this->getLogFile($parameters);

        $msg = "------------------------{$parameters['apiOperation']}----------------------------------\n";

        $msg .= "CURL-URL----------------------------------\n {$url} \n";
        $msg .= "CURL-REQUEST-FIELDS-----------------------\n".print_r($request, 1)." \n";
        $msg .= "CURL-RESPONSE-----------------------------\n".print_r($response, 1)." \n";
        if ($errno) {
            $msg .= "CURL-ERROR--------------------------------\n ({$errno}): {$error_message} \n";
        }

        $this->writeLog($logFile, $msg);
    }

    /**
     * This method returns the log file to write to
     *
     * @param array $params
     *
     * @return string the full path of the log file
     */
    public function getLogFile($params)
    {
        $dir = $this->container->get('kernel')->getRootDir()."/logs/payments/areeba/";

        $filename = sprintf("%s_%s_%s.log", $params['timeStart'], $params['order.id'], $params['apiOperation']);

        return $dir.$filename;
    }

    /**
     * This method writes a message to the log file
     *
     * @param string $fileName The log file's name
     * @param string $msg
     *
     * @return
     */
    private function writeLog($fileName, $msg)
    {
        // check if directory exists; otherwise create it!
        $pathinfo = pathinfo($fileName);
        if (!$this->container->get("TTFileUtils")->fileExists($pathinfo['dirname'], true)) {
            mkdir($pathinfo['dirname'], 0777, true);
        }

        $logFile = fopen($fileName, "a");

        fwrite($logFile, $msg."\n");
        fclose($logFile);
    }
}
