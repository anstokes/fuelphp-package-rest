<?php

namespace REST;

class Model_Helper
{
	
	static $json = null;
	
	
	protected static function readJson($force = false) {
		if (!static::$json || $force) {
			$body = file_get_contents('php://input');
			if ($json = json_decode($body)) {
				static::$json = $json;
			}
		}
		
		return static::$json;
	}
	
	
	public static function json($value, $default = null)
	{
		if ($json = static::readJson()) {
			if (isset($json->{$value})) {
				return $json->{$value};
			}
		}
		
		return $default;
	}
	
}
