<?php

namespace Core;

use Core\Traits\DBConnectionTrait;

abstract class BaseEntity
{
    use DBConnectionTrait;
}