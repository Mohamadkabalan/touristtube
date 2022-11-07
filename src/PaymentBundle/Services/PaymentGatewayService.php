<?php
namespace PaymentBundle\Services;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

use PaymentBundle\Model\PaymentGatewayFactory;
use PaymentBundle\Repository\PaymentRepository;
/**
 * @Service("payment_gateway_service")
 */
class PaymentGatewayService
{
	
	/**
	 * @var PaymentGatewayFactory
	 */
	private $paymentGateway;

	/**
	 * @var PaymentRepository
	 */
	private $repository;

	/**
	 * @var EventDispatcherInterface
	 */
	private $dispatcher;

	/**
	 * @InjectParams({
	 *      "paymentGatewayFactory" = @Inject("payment_gateway_factory"),
	 *      "paymentRepository" = @Inject("payment_repository"),
	 * })
	 */
	public function __construct(PaymentGatewayFactory $paymentGatewayFactory, PaymentRepository $paymentRepository){
		$this->paymentGateway = $paymentGatewayFactory;
		$this->repository = $paymentRepository;
	}

	/**
	 * PaymentGateway
	 * 
	 * @return Object PaymentGatewayInterface
	 */
	function getPaymentGateway( ) {
		return $this->paymentGateway;
	}

	/**
	 * PaymentRepository
	 * 
	 * @return Object PaymentRepository
	 */
	function getRepository( ) {
		return $this->repository;
	}
}