<?php
namespace PaymentBundle\Model;

interface PaymentGatewayInterface
{
	/**
	 * Set Configuration
	 *
	 * @return mixed
	 */
	function setConfig();

	/**
	 * Authorize the call to the endpoint gateway
	 *
	 * @return mixed
	 */
	function authorize( array $param );

	/**
	 * Get the response
	 *
	 * @return mixed
	 */
	function getResponse();

	/**
	 * Get the error if any otherwise false
	 *
	 * @return mixed
	 */
	function getError();

	/**
	 * check if http referer match with the provider url
	 *
	 * @return boolean
	 */
	function isMatchHttpReferer( $url );
}