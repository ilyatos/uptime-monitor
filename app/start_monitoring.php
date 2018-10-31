<?php

require_once 'boot.php';

use Monitor\Monitor;

$monitor = new Monitor();
try {
    $monitor->run();
} catch (\Monitor\Exceptions\CurlExecutionException $e) {
    \Core\Error::exceptionHandler($e);
}
