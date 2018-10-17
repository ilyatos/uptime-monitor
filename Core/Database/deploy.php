<?php

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use Core\Database\CreateTable;

$init = new CreateTable();
$init->run();