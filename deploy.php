<?php

require_once 'app/boot.php';

use App\Database\CreateTables;
use App\Database\SeedTables;

/**
 * Creating and seeding the table
 */
//CreateTables::run();
SeedTables::run();
