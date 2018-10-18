<?php

namespace App\Entities;

use Core\BaseEntity;

class Service extends BaseEntity
{
    protected static function getTableName()
    {
        return 'services';
    }
}