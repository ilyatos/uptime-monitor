<?php

namespace Monitor\Entities;

use Core\BaseEntity;

class Service extends BaseEntity
{
    public static function all(array $columns = [])
    {
        $connection = self::getConnection();

        $sql = '';

        if (!empty($columns)) {
            foreach ($columns as $column) {
                $sql = $sql . $column . ',';
            }
        } else {
            $sql = '*';
        }

        $sql = rtrim($sql, ',');

        $stmt = $connection->prepare("SELECT $sql FROM `services`");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function store(array $values)
    {
        
    }

    public static function updateWhere(array $updatedValues, string $column, string $param)
    {
        $connection = static::getConnection();

        $sql = '';

        foreach ($updatedValues as $key => $value) {
            $sql = $sql . $key . '=' . '\'' . $value . '\'' . ',';
        }

        $sql = rtrim($sql, ',');

        $query = $connection->prepare("UPDATE `services` SET $sql WHERE $column=:p");

        $query->bindParam(':p', $param);

        return $query->execute();
    }
}