<?php

declare(strict_types=1);

define('ROOT', dirname(__DIR__));

/**
 * Autoloader.
 */
require_once ROOT . '/vendor/autoload.php';

/**
 * Exception handling.
 */
//error_reporting(E_ALL);
//set_error_handler('Core\Error::errorHandler');
//set_exception_handler('Core\Error::exceptionHandler');

/**
 * Dotenv supporting.
 */
if (file_exists(ROOT . '/.env')) {
    $de = new Dotenv\Dotenv(ROOT);
    $de->load();
}
