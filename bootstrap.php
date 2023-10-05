<?php

use Fuel\Core\Autoloader;

Autoloader::add_classes([
    'Anstech\Rest\Controller\Cors' => __DIR__ . '/classes/controller/cors.php',
    'Anstech\Rest\Json\Helper'     => __DIR__ . '/classes/json/helper.php',
    'Anstech\Rest\Json\Schema'     => __DIR__ . '/classes/json/schema.php',
    'Anstech\Rest\Json\Token'      => __DIR__ . '/classes/json/token.php',
]);
