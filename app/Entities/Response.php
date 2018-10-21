<?php

namespace App\Entities;

use Core\BaseEntity;

class Response extends BaseEntity
{
    /**
     * Returns the table name.
     *
     * @return string
     */
    protected static function getTableName()
    {
        return 'responses';
    }
}