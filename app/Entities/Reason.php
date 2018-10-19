<?php

namespace App\Entities;

use Core\BaseEntity;

class Reason extends BaseEntity
{
    /**
     * Returns the table name.
     *
     * @return string
     */
    static protected function getTableName()
    {
        return 'reasons';
    }
}