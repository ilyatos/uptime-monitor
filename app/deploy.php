<?php

require_once 'boot.php';
require_once 'boot_orm.php';

use App\Database\CreateTables;
use App\Database\SeedTables;

/**
 * Creating and seeding the table
 */
CreateTables::run();
SeedTables::run();
