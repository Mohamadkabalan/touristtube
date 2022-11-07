<?php

namespace PaymentBundle\vendors\paytabs\v3;


use PaymentBundle\vendors\paytabs\v3\Model\PaytabsResponse;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;
use Symfony\Component\Yaml\Yaml;
use PaymentBundle\vendors\paytabs\v3\PaytabsResponseCodes;
use TTBundle\Utils\TTSerializerUtils;
use PaymentBundle\vendors\Config as PaymentConfig;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class PaytabsHandler implements \PaymentBundle\Services\PaymentServiceProvider
{
    
    protected $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->config = new PaymentConfig($container);
        $this->TTSerializer = new TTSerializerUtils();
    }
    
    public function voidOnHoldPayment($paymentObject)
    {
        $params['merchant_id']    = $this->config->merchant_email;
        $params['secret_key']     = $this->config->secret_key;
        $params['transaction_id'] = $paymentObject->getFortId();
        $params['order_id']       = $paymentObject->getUuid();
        $params['refund_amount']  = $paymentObject->getUuid();
        $params['refund_reason']  = self::REFUND_REASON;
        $response                 = $this->refundTransaction($params);
        $refundObject             = json_encode($response);
        $object                   = $this->TTSerializer->deserializeJsonToObject($refundObject, PaytabsResponse::class);
        //
        
        $paytabsResponseCodes      = new PaytabsResponseCodes();
        $param['status']           = $paytabsResponseCodes->getGenericResponseCode($object->getResponse_code());
        $param['response_code']    = $object->getResponse_code();
        $param['response_message'] = $object->getResult();
        
        return $param;
    }
    
    public function tokenise($param, $uuid, $paymentInfo)
    {
        
    }
    
    public function captureOnHoldPayment($trxId)
    {
        $captureResponse = array('response_message' => 'Your transaction is succesfully completed.', 'status' => '4', 'response_code' => '100', 'command' => 'PURCHASE');
        $captureStatus   = array("success" => true, "data" => $captureResponse);
        
        return $captureStatus;
    }
    
    public function processPayment($trxId, $paymentInfo, $resultJson)
    {
        
    }
    
    public function processResponse($trxId, $paymentInfo, $jsonResponse)
    {
        
    }
    
    public function refund($trxId)
    {
        $params['merchant_id']    = $this->config->merchant_email;
        $params['secret_key']     = $this->config->secret_key;
        $params['transaction_id'] = $paymentObject->getFortId();
        $params['order_id']       = $paymentObject->getUuid();
        $params['refund_amount']  = $paymentObject->getUuid();
        $params['refund_reason']  = self::REFUND_REASON;
        $response                 = $this->refundTransaction($params);
        $refundObject             = json_encode($response);
        $object                   = $this->TTSerializer->deserializeJsonToObject($refundObject, PaytabsResponse::class);
        //
        
        $paytabsResponseCodes      = new PaytabsResponseCodes();
        $param['status']           = $paytabsResponseCodes->getGenericResponseCode($object->getResponse_code());
        $param['response_code']    = $object->getResponse_code();
        $param['response_message'] = $object->getResult();
        
        return $param;
    }
    
    /**
     * This function checks the credentials of out paytabs account if they are valid or not
     */
    private function validateKey($parameters)
    {
        $url      = $this->config->keyValidationUrl;
        $response = $this->sendCurlRequest($url, $parameters);
        
        return $response;
    }
    
    /**
     * This function creates a paytabs checkout form outside our website
     */
    private function createPaytabsPage($parameters)
    {
        $url      = $this->config->createPayPageUrl;
        $response = $this->sendCurlRequest($url, $parameters);
        
        return $response;
    }
    
    /**
     * This function verify the status of the payment if it is valid or nor with all the required information
     */
    private function verifyPayment($parameters)
    {
        $url      = $this->config->verifyPaymentUrl;
        $response = $this->sendCurlRequest($url, $parameters);
        
        return $response;
    }
    
    /**
     * This funciton is responsible of refunding any given amount
     */
    private function refundTransaction($parameters)
    {
        $url      = $this->config->refundTransactionUrl;
        $response = $this->sendCurlRequest($url, $parameters);
        
        return $response;
    }
    
    private function sendCurlRequest($url, $parameters)
    {
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }
    
    public function generateMerchantReference($uuid)
    {
        return uniqid(substr($uuid, 5, 7));
    }
    
    /**
     * This function gets the returned object of paytabs and call paytabs verifyPayment API to check if we get the same response
     * and then checks the status of the returned response and match it with the tt status codes and fill the response object that will be returned to the
     * callback function in the controller
     * @param unknown $object
     * @return string
     */
    public function checkPaymentStatus($object)
    {
        
        $parameters['merchant_email'] = $this->config->merchant_email;
        $parameters['secret_key']     = $this->config->secret_key;
        //
        
        $parameters['transaction_id'] = $object->getTransaction_id();
        $parameters['order_id']       = $object->getOrder_id();
        $jsonResponse                 = $this->verifyPayment($parameters);
        
        $validationKeyObject            = $this->TTSerializer->deserializeJsonToObject($jsonResponse, PaytabsResponse::class, 'json');
        $paytabsResponseCodes           = new PaytabsResponseCodes();
        $genericResponseCodes           = $paytabsResponseCodes->getGenericResponseCode($validationKeyObject->getResponse_code());
        $merchant_reference             = $this->generateMerchantReference($validationKeyObject->getOrder_id());
        $genericCommands                = new ResponseStatusIdentifier();
        //
        $response['response_code']      = $validationKeyObject->getResponse_code();
        $response['response_message']   = mysql_escape_string($validationKeyObject->getResult());
        $response['fort_id']            = $validationKeyObject->getTransaction_id();
        $response['merchant_reference'] = $merchant_reference;
        $response['command']            = $genericCommands->getGenericCommand($genericResponseCodes);
        $response['status']             = $genericResponseCodes;
        
        if (ResponseStatusIdentifier::isSuccess($genericResponseCodes)) {
            $response['success'] = "true";
        } elseif (ResponseStatusIdentifier::isPending($genericResponseCodes)) {
            $response['success'] = "false";
        } elseif (ResponseStatusIdentifier::isFailure($genericResponseCodes)) {
            $response['success'] = "false";
        }
        return $response;
    }

}