<?php

require_once '../app/boot.php';
require_once '../app/boot_orm.php';

use Pecee\SimpleRouter\SimpleRouter;

/**
 * Routing.
 */
require_once ROOT . '/vendor/pecee/simple-router/helpers.php';
require_once ROOT . '/app/Http/routes.php';

SimpleRouter::start();
