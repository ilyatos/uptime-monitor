<?php

use Monitor\Monitor;

define('ROOT', __DIR__);

require_once ROOT . '/vendor/autoload.php';

/**
 * Exception handling
 */
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Dotenv supports
 */
if (file_exists(ROOT . '/.env')) {
    $de = new Dotenv\Dotenv(ROOT);
    $de->load();
}

$monitor = new Monitor();
$monitor->run();