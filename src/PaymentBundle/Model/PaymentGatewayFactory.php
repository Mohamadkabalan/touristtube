<?php
namespace PaymentBundle\Model;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * This is a class builder for payment gateway, 
 * Either it's Payfort or other payment provider, this is flexible enough to 
 * extend other payment provider in the future 
 */

final class PaymentGatewayFactory
{

	public function __construct( PaymentGatewayInterface $paymentGateway ){
		$this->paymentGateway = $paymentGateway;
	}	

	/**
	 * Setter Builder
	 *
	 * @param Object PaymentGatewayInterface
	 * @return Object PaymentGatewayFactory
	 */
	function setPaymentGateway(PaymentGatewayInterface $paymentGateway){
		$this->paymentGateway = $paymentGateway;

		return $this;
	}

	/**
	 * Class Builder
	 * @return Object PaymentGatewayInterface
	 */
	function build(){
		return $this->paymentGateway;
	}

}