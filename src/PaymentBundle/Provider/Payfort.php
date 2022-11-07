<?php

namespace PaymentBundle\Provider;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use PaymentBundle\Services\impl\ResponseStatusIdentifier;

/**
 * Payfort service API
 *
 * @Service("payfort_service_api")
 */
final class Payfort
{

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
	private $SHAType = 'sha256';

	private $arrCredential = array(
		'access_code' => 'lXJ9rz4VF2VVk7pRrRJy',
		'merchant_identifier' => 'qvnFNPMX',
		'language' => 'en',
	);

	/**
     * @var string  project root folder
     * change it if the project is not on root folder.
     */
	public $projectUrlPath = '';

	function __construct(){

	}

	function command( $command, array $param ) {

		if( $command == "TOKENIZATION" ){
			return $this->tokenize( $param );
		}

		if( $command == "PURCHASE" ){
			return $this->purchase( $param );
		}
	}

	/**
	 * calculate fort signature
	 * @param array $arrData
	 * @param string $signType request or response
	 * @return string fort signature
	 */
	protected function calculateSignature( $arrData, $signType = 'request' ){
		
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

	public function getUrl( $path ){

		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
		$url      = $protocol.$_SERVER['HTTP_HOST'].$this->projectUrlPath.'/'.$path;

		return $url;
	}

	/**
	 * Convert Amount with dicemal points
	 * @param decimal $amount
	 * @param string  $currencyCode
	 * @return decimal
	 */
	private function convertFortAmount($amount, $currencyCode)
	{
		$new_amount    = 0;
		$total         = $amount;
		$decimalPoints = $this->getCurrencyDecimalPoints($currencyCode);
		$new_amount    = round($total, $decimalPoints) * (pow(10, $decimalPoints));
		return $new_amount;
	}

	private function castAmountFromFort($amount, $currencyCode)
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
	private function getCurrencyDecimalPoints($currency)
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

	/**
	 * Ask Payfort to generate token based on HTTPs Form Post Request.
	 * @param  array $param
	 * @return array 
	 */
	protected function tokenize( array $param  ){

		$url = 'https://sbcheckout.payfort.com/FortAPI/paymentPage';

		//production url
		//$url = https://checkout.PayFort.com/FortAPI/paymentPage

		$request = $this->arrCredential;
		$request['merchant_reference'] = uniqid( substr( $param['uuid'], 5, 7 ) );
		$request['service_command'] = 'TOKENIZATION';
		$request['return_url'] = $this->getUrl('payment-callback-url?transaction_id='. $param['uuid'] );
		//$request['return_url'] = $this->getUrl('payment-MerchantPage?transaction_id='. $param['uuid'] );
		$request['signature'] = urlencode( $this->calculateSignature( $request, 'request' ) );
		$request = $request + [
			'card_number' => $param['card_number'],
			'expiry_date' => $param['expiry_date'],
			'card_holder_name' => $param['card_holder_name'],
			'card_security_code' => $param['card_security_code']
		];

		$response = $this->connect( $url, 'form', $request );

		return $response;
	}

	/**
	 * Request Payfort thru PURCHASE command using the token name received from the Tokenization process.
	 * @param  array $param
	 * @return array 
	 */
	protected function purchase( array $param  ){

		$url = 'https://sbpaymentservices.payfort.com/FortAPI/paymentApi';

		//production url
		//$url = https://paymentservices.payfort.com/FortAPI/paymentApi

		$request = $this->arrCredential;
		$request['merchant_reference'] = uniqid( substr( $param['uuid'], 5, 7 ) );
		$request['command'] = 'PURCHASE';
		//$request['return_url'] = $this->getUrl('payment-callback-url?transaction_id='. $param['uuid'] );
		$request['return_url'] = $this->getUrl('payment-processRequest?transaction_id='.$param['uuid']);
		$request = $request + [
			'token_name' => $param['token_name'],
			'customer_ip' => $_SERVER['REMOTE_ADDR'],
			'amount' => $this->convertFortAmount( $param['amount'], $param['currency'] ),
			'currency' => strtoupper( $param['currency'] ),
			'customer_email' => $param['customer_email'],
			'customer_name' => $param['customer_name']
		];
		$request['signature'] = urlencode( $this->calculateSignature( $request, 'request' ) );

		$response = $this->connect( $url, 'json', $request );

		return $response;
	}

	/**
	 * Connect to Payfort API
	 * @param  string $url the endpoint
	 * @param  string $http_header header field
	 * @return array|mixed
	 */
	function connect( $url, $http_header = 'json', array $fields ){

		$ch = curl_init();

		$HEADER_TYPE['json']     = 'Content-Type: application/json;charset=UTF-8';
		$HEADER_TYPE['form']     = 'multipart/form-data';
		$HEADER_TYPE['form-url'] = 'application/x-www-form-urlencoded';

		$useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0";
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			$HEADER_TYPE[$http_header]
		));

		if( $http_header == 'form' ){
			$request = "";
			foreach ( $fields as $key => $value) {
				$request .= $key.'='.$value.'&';
			}
			$request = rtrim($request, '&');
		} else {
			$request = json_encode($fields);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, "compress, gzip");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // allow redirects

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return into a variable
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); // The number of seconds to wait while trying to connect
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		# enabled referer
		//curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_REFERER, 'https://www.payfort.com' );
		# Send request.
		$response = curl_exec($ch);

		/*
		echo "<pre>";
		print_r($response);
		echo "</pre>";

		$tes_result = json_decode($response, true);

		echo "<br/>";
		echo "-------------------------------Response--------------------------------------";
		echo "<br/>";
		echo "<pre>";
		print_r($tes_result);
		echo "</pre>";			
		echo "<br/>";
		echo "-----------------------------------------------------------------------------";
		echo "<br/>";
		echo "<br/>";

		echo "-------------------------------curl_error Data-------------------------------";
		echo "<br/>";
		var_export( curl_error($ch) );	
		echo "<br/>";
		echo "-----------------------------------------------------------------------------";
		echo "<br/>";
		echo "<br/>";

		$info = curl_getinfo($ch); 

		echo "-------------------------------curl_getinfo----------------------------------";
		echo "<br/>";
		echo "<br/>";
		echo "<pre>";
		var_dump($info); 
		echo "<pre>";	
		echo "<br/>";
		echo "-----------------------------------------------------------------------------";
		echo "<br/>";
		echo "<br/>";
		*/

		curl_close($ch);

		$array_result = json_decode($response, true);

		if (!$response || empty($array_result)) {
			return false;
		} else {
			$array_result['success']    = ResponseStatusIdentifier::isSuccess($array_result['status']);
		}

		return $array_result;
	} 
}
