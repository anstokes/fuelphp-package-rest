<?php

namespace REST;

/**
 * Simple extension of the FuelPHP REST Controller
 *
 * Add supports for cross-site (XS) requests and CORS Preflight
 */
class Controller_RestXS extends \Controller_Rest
{
	
	// Allowed headers, origin
	protected static $headers = 'Content-Type';
	protected static $origin = '*';
	
	protected static function headers()
	{
		return static::$headers;
	}
	
	protected static function origin()
	{
		return static::$origin;
	}
	
	
	// Allow cross domain requests
	public function after($response)
	{
		$customResponse = parent::after($response);
		$customResponse->set_header('Access-Control-Allow-Headers', static::headers());
		$customResponse->set_header('Access-Control-Allow-Origin', static::origin());
		return $customResponse;
	}
	
	
	/**
	 * Set basenode, and ensure response is in the same format as the input
	 *
	 * @param type $inputType
	 * @param type $prefix
	 */
	protected function configureResponse()
	{
		$this->format = $this->inputType();
		// $this->xml_basenode = $prefix . static::$callType;
	}
	
	
	/**
	 * CORS Preflight response
	 *
	 * @return \Response The CORS Preflight response
	 */
	protected function corsPreflight()
	{
		return \Response::forge(
			null,
			204,
			array(
			'Connection' => 'keep-alive',
			'Access-Control-Allow-Origin' => static::origin(),
			'Access-Control-Allow-Headers' => static::headers(),
			'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE'
			)
		);
	}
	
	
	/**
	 * The type of input request
	 *
	 * @return string	json, xml based on request
	 */
	protected function inputType()
	{
		return 'json';
	}
	
	
	// Router, with CORS Preflight support
	public function router($resource, $arguments)
	{
		// Check for CORS Preflight request
		if (\Request::active()->get_method() == 'OPTIONS') {
			return $this->corsPreflight();
		}
		
		// Configure response
		$this->configureResponse();

		// Use parent method
		return parent::router($resource, $arguments);
	}
}
