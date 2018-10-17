<?php

namespace Monitor\Entities;

use Core\BaseEntity;

class Service extends BaseEntity
{
    public static function get()
    {
        $connection = self::getConnection();
    }
}