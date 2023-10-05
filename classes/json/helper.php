<?php

namespace Anstech\Rest\Json;

class Helper
{
    protected static $json = null;


    /**
     * Read JSON body from request
     *
     * @param boolean $force Whether to re/read, otherwise use static value where available
     */
    protected static function readJson($force = false)
    {
        if (! static::$json || $force) {
            $body = file_get_contents('php://input');
            if ($json = json_decode($body)) {
                static::$json = $json;
            }
        }

        return static::$json;
    }


    /**
     * Read individual parameter from JSON
     *
     * @param string $value Name of parameter to read
     * @param string $default Value to use if parameter not found
     */
    public static function json($parameter, $default = null)
    {
        if ($json = static::readJson()) {
            if (isset($json->{$parameter})) {
                return $json->{$parameter};
            }
        }

        return $default;
    }
}
