<?php

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use Monitor\Monitor;

$monitor = new Monitor();
$monitor->run();