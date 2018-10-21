<?php

namespace Core;

use Core\Traits\DBConnectionTrait;

abstract class BaseEntity
{
    use DBConnectionTrait;

    abstract protected static function getTableName();

    /**
     * Get one record with satisfied parameters.
     *
     * @param string $column
     * @param string $param
     * @return mixed
     */
    public static function findOneWhere(string $column, string $param)
    {
        $connection = static::getConnection();

        $query = $connection->prepare(sprintf("SELECT * FROM %s WHERE $column=:p LIMIT 1", static::getTableName()));

        $query->bindParam(':p', $param);

        $query->execute();

        return $query->fetch();
    }

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

        $stmt = $connection->prepare(sprintf("SELECT $sql FROM %s", static::getTableName()));
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

        $query = $connection->prepare(sprintf("INSERT INTO %s ($columns) VALUES ($values)", static::getTableName()));

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

        $query = $connection->prepare(sprintf("UPDATE %s SET $sql WHERE $column=:p", static::getTableName()));

        $query->bindParam(':p', $param);

        return $query->execute();
    }

    /**
     * Check if record exists in a table.
     *
     * @param string $column
     * @param string $param
     * @return bool
     */
    public static function existsWhere(string $column, string $param)
    {
        $connection = static::getConnection();

        $query = $connection->prepare(sprintf("SELECT EXISTS(SELECT 1 FROM %s WHERE $column=:p) AS exist LIMIT 1",
            static::getTableName()));

        $query->bindParam(':p', $param);

        $query->execute();

        $fetched = $query->fetch();

        return $fetched['exist'] == 1 ? true : false;
    }
}