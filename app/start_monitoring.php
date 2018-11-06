<?php

require_once 'boot.php';
require_once 'boot_orm.php';

use Monitor\Monitor;

$monitor = new Monitor();

try {
    $monitor->run();
} catch (\Monitor\Exceptions\CurlExecutionException $e) {
    \Core\Error::exceptionHandler($e);
}
