<?php

require_once '../app/boot.php';

use Pecee\SimpleRouter\SimpleRouter;

/**
 * Routing.
 */
require_once ROOT . '/vendor/pecee/simple-router/helpers.php';
require_once ROOT . '/app/Http/routes.php';

SimpleRouter::start();
