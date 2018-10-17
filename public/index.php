<?php

use Pecee\SimpleRouter\SimpleRouter;

define('ROOT', dirname(__DIR__));

/**
* Autoloader
*/
require_once ROOT . '/vendor/autoload.php';

/**
 * Routing
 */
require_once ROOT . '/vendor/pecee/simple-router/helpers.php';
require_once ROOT . '/monitor/routes.php';

SimpleRouter::start();