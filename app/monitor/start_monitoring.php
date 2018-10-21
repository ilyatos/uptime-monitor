<?php

define('ROOT', __DIR__ . '/../../');

require_once ROOT . 'vendor/autoload.php';

/**
 * Exception handling
 */
set_exception_handler('Core\Error::exceptionHandler');

use Monitor\Monitor;

$monitor = new Monitor();
$monitor->run();