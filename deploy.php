<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use App\Database\CreateTables;
use App\Database\SeedTables;

/**
 * Create a database, enter login, password, host, db_name
 */


/**
 * Creating and seeding the table
 */
CreateTables::run();
SeedTables::run();
