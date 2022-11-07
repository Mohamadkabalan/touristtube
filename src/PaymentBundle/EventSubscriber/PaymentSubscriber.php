<?php

namespace PaymentBundle\EventSubscriber;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Event\FilterDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use PaymentBundle\Model\PaymentGatewayInterface;
use PaymentBundle\Exception\PaymentProviderException;

/**
 * Payment Listener
 *
 * This class will check the Payment provider response, If its fail, the form will throw an error.
 */
class PaymentSubscriber implements EventSubscriberInterface
{
	/**
     * @var paymentGateway
     */
	private $paymentGateway;

	function __construct(PaymentGatewayInterface $paymentGateway){
		$this->paymentGateway = $paymentGateway;
	}
	
	public static function getSubscribedEvents(){
		return array(
			FormEvents::SUBMIT => 'onBindSubmitPayment'
		);
	}
	
	public function onBindSubmitPayment(FormEvent $event){
		$form = $event->getForm();	
		$payment = $form->getData();

		$param = array();
		$param['uuid'] = $payment->getUuid();
		$param['card_number'] = $form->get('cardnumber')->getData();

		if( !strlen( trim( $form->get('cardholder')->getData() ) ) ){
			$form->get( 'cardholder' )->addError(new FormError(''));
		}
		
		if( !strlen( trim( $form->get('cardnumber')->getData() ) ) ){
			$form->get( 'cardnumber' )->addError(new FormError(''));
		}

		if( !strlen( trim( $form->get('cvv')->getData() ) ) ){
			$form->get( 'cvv' )->addError(new FormError(''));
		}

		if( !strlen( trim( $form->get('card_expire_year')->getData() ) ) ){
			$form->get( 'card_expire_year' )->addError(
				new FormError( 'Expiration year is required' )
			);
		}

		if( !strlen( trim($form->get('card_expire_month')->getData() ) ) ){
			$form->get( 'card_expire_month' )->addError(
				new FormError( 'Expiration month is required' )
			);
		}

		$form->add('server_callback_data', 'text', array('required' => false,'mapped' => false));

		$param['expiry_date'] = sprintf(
			'%d%s', 
			substr( $form->get('card_expire_year')->getData(), 2 ), 
			$form->get('card_expire_month')->getData() 
		);

		$param['card_holder_name'] = $form->get('cardholder')->getData();
		$param['card_security_code'] = $form->get('cvv')->getData();

		if( !self::indent((string) $form->getErrors(true, false), 0) ) {

			$tokenize = $this->paymentGateway->tokenize( $param );

			$tokenize['command'] = "TOKENIZATION";
			
			# Ensure to update the tables related to payment after calling the gateway
			$this->updatePayment( $payment, $tokenize );
			
			if( !$tokenize[ 'success' ] ){
				$form->get( 'server_error_callback' )->addError(
					new FormError( $tokenize['response_message'] )
				);
			} else {
				$request = array();
				$request['uuid'] = $payment->getUuid();
				$request['token_name'] = $tokenize[ 'token_name' ];
				$request['amount'] = $payment->getAmount();
				$request['currency'] = $payment->getCurrency();
				$request['customer_email'] = $payment->getEmail();
				$request['customer_name'] = $payment->getCustomerName();

				$purchase = $this->paymentGateway->purchase( $request );
				
				# Ensure to update the tables related to payment after calling the gateway
				$this->updatePayment( $payment, $purchase );

				# Dont pass the credential and card data to url
				unset($purchase['card_holder_name']);
				unset($purchase['signature']);
				unset($purchase['merchant_identifier']);
				unset($purchase['access_code']);
				unset($purchase['fort_id']);
				unset($purchase['card_number']);

				if (isset($purchase['3ds_url'])){
					$purchase['return_url'] = $purchase['3ds_url'];
					unset($purchase['3ds_url']);
					$purchase['success']    = true;
					$form->get('server_callback_data')->setData(json_encode($purchase));
				} else {

					if( !$purchase[ 'success' ] ){
						$form->get( 'server_error_callback' )->addError(
							new FormError( $purchase['response_message'] )
						);
					} else {
						$purchase['success']    = true;
					}

					$form->get('server_callback_data')->setData(json_encode($purchase));
				}
			}
		} else {
			$form->get( 'server_error_callback' )->addError(
				new FormError( "Opps! Please correct the error indicated below" )
			);
		}
	}

	/**
	 * Update Payment table
	 * @param  object $Payment
	 * @param  array  $param Fields to update
	 * @return void
	 */
	private function updatePayment( $payment, array $param ){
				
		try {

			$payment->setCommand( $param[ 'command' ] );
			$payment->setStatus( $param[ 'status' ] );
			$payment->setResponseCode( $param[ 'response_code' ] );
			$payment->setResponseMessage( $param[ 'response_message' ] );
			$payment->setMerchantReference( $param[ 'merchant_reference' ] );

			$this->paymentGateway->getRepository()->save( $payment );

			$this->createPaymentTransactionFeedback( $payment, $param );

		} catch( \Exception $e ) {
			throw new PaymentProviderException('Payment Provider security error. Make sure to add your URL to the Dashboard Security Console on the provider');
		}
	}

	/**
	 * Create a new record to the payment_transaction_feedback table
	 * @return Object PaymentTransactionFeedback
	 */
	private function createPaymentTransactionFeedback( $payment, array $param ){
		return $this->paymentGateway->getRepository()->createPaymentTransactionFeedback( $payment, $param );
	}

	/**
     * Utility function for indenting multi-line strings.
     *
     * @param string $string The string
     * @param int    $level  The number of spaces to use for indentation
     *
     * @return string The indented string
     */
    private static function indent($string, $level)
    {
        $indentation = str_repeat(' ', $level);

        return rtrim($indentation.str_replace("\n", "\n".$indentation, $string), ' ');
    }
}
