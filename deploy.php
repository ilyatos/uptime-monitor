<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use Core\Database\CreateTable;

/**
 * Creating table
 */
$createTableAtDB = new CreateTable();
$createTableAtDB->run();