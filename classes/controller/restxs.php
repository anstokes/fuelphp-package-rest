<?php

namespace REST;

/**
 * Simple extension of the FuelPHP REST Controller
 * 
 * Add supports for cross-site (XS) requests and CORS Preflight
 */
class Controller_RestXS extends \Controller_Rest
{
	
	// Allow cross domain requests
	public function after($response)
	{
		$customResponse = parent::after($response);
		$customResponse->set_header('Access-Control-Allow-Origin', '*');
		return $customResponse;
	}
	
	// Router, with CORS Preflight support
	public function router($resource, $arguments)
	{
		// Check for CORS Preflight request
		if (\Request::active()->get_method() == 'OPTIONS') {
			return $this->corsPreflight();
		}

		// Use parent method
		return parent::router($resource, $arguments);
	}
	
	/**
	 * CORS Preflight response
	 *
	 * @return type
	 */
	protected function corsPreflight()
	{
		return \Response::forge(
			null,
			204,
			array(
			'Connection' => 'keep-alive',
			'Access-Control-Allow-Origin' => '*',
			'Access-Control-Allow-Headers' => 'Content-Type',
			'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE'
			)
		);
	}
}
