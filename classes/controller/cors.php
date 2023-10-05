<?php

namespace Anstech\Rest\Controller;

use Fuel\Core\Controller_Rest;
use Fuel\Core\Response;
use Fuel\Core\Request;

/**
 * Simple extension of the FuelPHP REST Controller
 *
 * Add supports for cross-site (XS) requests and CORS Preflight
 */
class Cors extends Controller_Rest
{
    // Allowed headers, origin
    protected static $headers = 'Content-Type';
    protected static $origin = '*';

    protected $response;

    protected static function headers()
    {
         return static::$headers;
    }

    protected static function origin()
    {
         return static::$origin;
    }

    /**
     * Allow cross domain requests
     *
     * @param Response $response
     *
     * @return Response The modified response with Access-Control headers set
     */
    public function after($response)
    {
        $custom_response = parent::after($response);
        $custom_response->set_header('Access-Control-Allow-Headers', static::headers());
        $custom_response->set_header('Access-Control-Allow-Origin', static::origin());
        return $custom_response;
    }

    /**
     * Set basenode, and ensure response is in the same format as the input
     */
    protected function configureResponse()
    {
        $this->format = $this->inputType();
        // $this->xml_basenode = $prefix . static::$callType;
    }


    /**
     * CORS Preflight response
     *
     * @return Response The CORS Preflight response
     */
    protected function corsPreflight()
    {
        return Response::forge(
            null,
            204,
            [
                'Connection'                   => 'keep-alive',
                'Access-Control-Allow-Origin'  => static::origin(),
                'Access-Control-Allow-Headers' => static::headers(),
                'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE',
            ]
        );
    }


    /**
     * The type of input request
     *
     * @return string json, xml based on request
     */
    protected function inputType()
    {
        return 'json';
    }


    /**
     * Router, with CORS Preflight support
     *
     * @param string $resource
     * @param array $arguments
     */
    public function router($resource, $arguments)
    {
        // Check for CORS Preflight request
        if (Request::active()->get_method() == 'OPTIONS') {
            return $this->corsPreflight();
        }

        // Configure response
        $this->configureResponse();

        // Use parent method
        return parent::router($resource, $arguments);
    }
}
