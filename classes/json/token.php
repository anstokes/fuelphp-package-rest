<?php

namespace Anstech\Rest\Json;

use Fuel\Core\Arr;

class Token
{
    protected const SECRET = '9bc819d41f5df8f5d5278b007e556a112e56b40e66f87f2ef28ec7a4a746181df14a84cf14929ae59d1d6a71954e767283a819d5a0e8d9e05cb6d6b9c1bcbbd3';

    protected static $headers = [
        'alg' => 'HS256',
        'typ' => 'JWT',
    ];

    /**
     * Generates a JWT token, including the provided payload
     * @param array $payload
     * @param array $headers
     * @return string
     */
    public static function generate($payload = [], $headers = [])
    {
        // Build the headers
        $headers = $headers ?: static::$headers;
        $headers_encoded = static::base64UrlEncode(json_encode($headers));

        // Build the payload
        $payload_encoded = static::base64UrlEncode(json_encode($payload));

        // Build the signature
        $signature = hash_hmac('SHA256', "{$headers_encoded}.{$payload_encoded}", static::SECRET);

        // Build and return the token
        return "{$headers_encoded}.{$payload_encoded}.{$signature}";
    }

    /**
     * Validate provided JWT token
     * Returns payload, if valid, otherwise false
     * @param string $jwt The JWT token to be validated
     * @return false|array
     */
    public static function validate($jwt)
    {
        // Split the jwt
        $tokenParts = explode('.', $jwt);
        $headers = json_decode(base64_decode($tokenParts[0]), true);
        $payload = json_decode(base64_decode($tokenParts[1]), true);

        // Check the expiration time - note this will cause an error if there is no 'exp' claim in the JWT
        $expiration = Arr::get($payload, 'exp', time());
        $isTokenExpired = ($expiration - time()) < 0;

        // Build a token based on the header and payload using the secret
        $generated = static::generate($payload, $headers);

        // Verify it matches the token provided
        $tokensMatch = ($generated === $jwt);
        if (! $isTokenExpired && $tokensMatch) {
            return $payload;
        }

        return false;
    }

    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
