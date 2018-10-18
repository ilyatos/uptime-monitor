<?php

namespace App\Entities;

use Core\BaseEntity;

class Response extends BaseEntity
{
    static protected function getTableName()
    {
        return 'responses';
    }
}