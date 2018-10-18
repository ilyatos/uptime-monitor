<?php

namespace App\Entities;

use Core\BaseEntity;

class Reason extends BaseEntity
{
    static protected function getTableName()
    {
        return 'reasons';
    }
}