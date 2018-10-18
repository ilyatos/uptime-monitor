<?php

namespace Core;

use Core\Traits\DBConnectionTrait;

abstract class BaseEntity
{
    use DBConnectionTrait;

    abstract protected static function getTableName();

    /**
     * Get columns as an array, if it is empty, than return all columns.
     *
     * @param array $columns
     * @return array
     */
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

        $stmt = $connection->prepare("SELECT $sql FROM " . static::getTableName());
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Add a row into the database.
     *
     * @param array $data
     * @return bool
     */
    public static function store(array $data)
    {
        $connection = static::getConnection();

        $columns = '';
        $values = '';

        foreach ($data as $column => $value) {
            $columns = $columns . $column . ',';
            $values = $values . '\'' . $value . '\'' . ',';
        }

        $columns = rtrim($columns, ',');
        $values = rtrim($values, ',');

        $query = $connection->prepare("INSERT INTO " . static::getTableName() . " ($columns) VALUES ($values)");

        return $query->execute();
    }

    /**
     * Update values in row, where column=param.
     *
     * @param array $updatedValues
     * @param string $column
     * @param string $param
     * @return bool
     */
    public static function updateWhere(array $updatedValues, string $column, string $param)
    {
        $connection = static::getConnection();

        $sql = '';

        foreach ($updatedValues as $key => $value) {
            $sql = $sql . $key . '=' . '\'' . $value . '\'' . ',';
        }

        $sql = rtrim($sql, ',');

        $query = $connection->prepare("UPDATE ". static::getTableName() ." SET $sql WHERE $column=:p");

        $query->bindParam(':p', $param);

        return $query->execute();
    }
}