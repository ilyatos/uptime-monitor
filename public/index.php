<?php

use Pecee\SimpleRouter\SimpleRouter;

define('ROOT', dirname(__DIR__));

/**
* Autoloader
*/
require_once ROOT . '/vendor/autoload.php';

/**
 * Exception handling
 */
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
require_once ROOT . '/vendor/pecee/simple-router/helpers.php';
require_once ROOT . '/app/http/routes.php';

SimpleRouter::start();
