<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use App\Database\CreateTables;
use App\Database\SeedTables;

/**
 * Creating and seeding the table
 */
CreateTables::run();
SeedTables::run();
