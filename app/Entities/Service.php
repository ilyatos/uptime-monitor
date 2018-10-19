<?php

namespace App\Entities;

use Core\BaseEntity;

class Service extends BaseEntity
{
    /**
     * Returns the table name.
     *
     * @return string
     */
    protected static function getTableName()
    {
        return 'services';
    }
}