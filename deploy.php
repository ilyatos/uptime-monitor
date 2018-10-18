<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use Monitor\Database\CreateTables;
use Monitor\Database\SeedTables;

/**
 * Create a database, enter login, password, host, db_name
 */


/**
 * Creating and seeding the table
 */
CreateTables::run();
SeedTables::run();
