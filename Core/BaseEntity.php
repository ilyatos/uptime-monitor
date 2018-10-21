<?php

namespace Core;

use Core\Traits\DBConnectionTrait;
use Core\Helpers\SQLBuilder;

abstract class BaseEntity
{
    use DBConnectionTrait;

    abstract protected static function getTableName();

    /**
     * Get record with satisfied parameters.
     *
     * @return SQLBuilder
     */
    public static function find()
    {
        return new SQLBuilder(sprintf("SELECT * FROM %s ", static::getTableName()));
    }


    /**
     * Get columns as an array, if it is empty, than return all columns.
     *
     * @param array $columns
     * @return array
     */
    public static function all(array $columns = []): array
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

        $helper = new SQLBuilder(sprintf("SELECT $sql FROM %s", static::getTableName()));

        return $helper->fetchAll();
    }

    /**
     * Add a row into the database.
     *
     * @param array $data
     * @return bool
     */
    public static function store(array $data): bool
    {
        $columns = '';
        $values = '';

        foreach ($data as $column => $value) {
            $columns = $columns . $column . ',';
            $values = $values . '\'' . $value . '\'' . ',';
        }

        $columns = rtrim($columns, ',');
        $values = rtrim($values, ',');

        $helper = new SQLBuilder(sprintf("INSERT INTO %s ($columns) VALUES ($values)", static::getTableName()));

        return $helper->execute();
    }

    /**
     * Update values in row, where column=param.
     *
     * @param array $updatedValues
     * @return bool
     */
    public static function update(array $updatedValues): bool
    {
        $sql = '';

        foreach ($updatedValues as $key => $value) {
            $sql = $sql . $key . '=' . '\'' . $value . '\'' . ',';
        }

        $sql = rtrim($sql, ',');

        $helper = new SQLBuilder(sprintf("UPDATE %s SET $sql", static::getTableName()));

        return $helper->execute();
    }

    /**
     * Check if record exists in a table.
     *
     * @param string $column
     * @param string $param
     * @return bool
     */
    public static function existsWhere(string $column, string $param): bool
    {
        $helper = new SQLBuilder(sprintf("SELECT EXISTS(SELECT 1 FROM %s WHERE $column='$param') AS exist",
            static::getTableName()));

        $fetched = $helper->limit(1)->fetch();

        return $fetched['exist'] == 1;
    }
}