<?php

namespace Core;

use Core\Traits\DBConnectionTrait;
use Core\Helpers\SqlHelper;

abstract class BaseEntity
{
    use DBConnectionTrait;

    abstract protected static function getTableName();

    /**
     * Get record with satisfied parameters.
     *
     * @return SqlHelper
     */
    public static function find()
    {
        return new SqlHelper(sprintf("SELECT * FROM %s ", static::getTableName()));
    }


    /**
     * Get columns as an array, if it is empty, than return all columns.
     *
     * @param array $columns
     * @return array
     */
    public static function all(array $columns = [])
    {
        $sql = '';

        if (!empty($columns)) {
            foreach ($columns as $column) {
                $sql = $sql . $column . ',';
            }
        } else {
            $sql = '*';
        }

        $sql = rtrim($sql, ',');

        $helper = new SqlHelper(sprintf("SELECT $sql FROM %s", static::getTableName()));

        return $helper->fetchAll();
    }

    /**
     * Add a row into the database.
     *
     * @param array $data
     * @return bool
     */
    public static function store(array $data)
    {
        $columns = '';
        $values = '';

        foreach ($data as $column => $value) {
            $columns = $columns . $column . ',';
            $values = $values . '\'' . $value . '\'' . ',';
        }

        $columns = rtrim($columns, ',');
        $values = rtrim($values, ',');

        $helper = new SqlHelper(sprintf("INSERT INTO %s ($columns) VALUES ($values)", static::getTableName()));

        return $helper->execute();
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