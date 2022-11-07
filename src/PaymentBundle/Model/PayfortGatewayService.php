<?php

namespace PaymentBundle\Model;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

use PaymentBundle\Repository\PaymentRepository;
use PaymentBundle\Provider\Payfort;

/**
 * @Service("payfort_gateway_service")
 */

class PayfortGatewayService implements PaymentGatewayInterface
{	
	/**
	 * @var PaymentRepository
	 */
	private $repository;

	/**
	 * @var PayfortApi
	 */
	private $payfortApi;

	/**
	 * @InjectParams({
	 *      "paymentRepository" = @Inject("payment_repository"),
	 *      "payfortApi" = @Inject("payfort_service_api")
	 * })
	 */
	public function __construct( PaymentRepository $paymentRepository, Payfort $payfortApi ){
		$this->repository = $paymentRepository;
		$this->payfortApi = $payfortApi;
	}

	/**
	 * Set Configuration
	 * @return mixed
	 */
	function setConfig( ){

	}

	/**
	 * PaymentRepository
	 * 
	 * @return Object PaymentRepository
	 */
	function getRepository( ) {
		return $this->repository;
	}

	/**
	 * Creates a token for the card details and sends it back to the Merchant.
	 *
	 * @return mixed
	 */
	function tokenize( array $param ){
		return $this->payfortApi->command( 'TOKENIZATION', $param );
	}

	/**
	 * Authorize Operation
	 *
	 * @return mixed
	 */
	function authorize( array $param ){
		return $this->payfortApi->command('AUTHORIZATION', $param );
	}

	/**
	 * Purchase Operation
	 *
	 * @return mixed
	 */
	function purchase( array $param ){
		return $this->payfortApi->command('PURCHASE', $param );
	}

	/**
	 * Refund Operation
	 *
	 * @return mixed
	 */
	function refund( array $param ){
		return $this->payfortApi->command('REFUND', $param );
	}

	/**
	 * Get the response
	 *
	 * @return mixed
	 */
	function getResponse( ){

	}

	/**
	 * Get the error if any, otherwise false
	 *
	 * @return mixed
	 */
	function getError( ){

	}

	/**
	 * check if http referer match with the provider url
	 *
	 * @return boolean
	 */
	function isMatchHttpReferer( $url ){
		$url = strtolower($url);

		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';

		$urls = array (
			'https://sbcheckout.payfort.com/',
			'https://sbpaymentservices.payfort.com/',
			'https://checkout.payfort.com/',
			'https://paymentservices.payfort.com/',
			'https://www.payfort.com',
			//$protocol . $_SERVER['HTTP_HOST']
		);
		
		foreach( $urls as $referer ){
			if ( strpos( $referer, $url ) !== false ) {
				return true;
			}
		}

		return false;
	}
}