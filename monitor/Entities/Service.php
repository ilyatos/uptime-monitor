<?php

namespace Monitor\Entities;

use Core\BaseEntity;

class Service extends BaseEntity
{
    public static function all()
    {
        $connection = self::getConnection();

        $stmt = $connection->prepare("SELECT * FROM `services`");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function store()
    {
        
    }

    public static function updateWhere()
    {
        
    }
}