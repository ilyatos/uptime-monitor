<?php

namespace Core;

use Core\Traits\DBConnectionTrait;
use Core\Helpers\SQLBuilder;

abstract class BaseEntity
{
    use DBConnectionTrait;

    abstract protected static function getTableName();

    /**
     * Get a record with satisfied parameters.
     *
     * @return SQLBuilder
     */
    public static function find(array $columns = []): SQLBuilder
    {
        $selectColumns = self::parseColumns($columns);

        $tableName = static::getTableName();

        return new SQLBuilder("SELECT $selectColumns FROM $tableName");
    }

    /**
     * Delete a record.
     *
     * @return SQLBuilder
     */
    public static function delete(): SQLBuilder
    {
        $tableName = static::getTableName();

        return new SQLBuilder("DELETE FROM $tableName");
    }

    /**
     * Get columns as an array, if it is empty, than return all columns.
     *
     * @param array $columns
     * @return array
     */
    public static function all(array $columns = []): array
    {
        $selectColumns = self::parseColumns($columns);

        $helper = new SQLBuilder(sprintf("SELECT %s FROM %s", $selectColumns, static::getTableName()));

        return $helper->getAll();
    }

    /**
     * Parse given columns
     *
     * @param array $columns
     * @return string
     */
    private static function parseColumns(array $columns = []): string
    {
        if (!empty($columns)) {
            $selectColumns = implode(',', $columns);
        } else {
            $selectColumns = '*';
        }

        return $selectColumns;
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

        $helper = new SQLBuilder("INSERT INTO " . static::getTableName() . " ($columns) VALUES ($values)");

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
        $helper = new SQLBuilder("SELECT EXISTS(SELECT 1 FROM " . static::getTableName() . " WHERE $column='$param') AS exist");

        $fetched = $helper->limit(1)->get();

        return $fetched['exist'] == 1;
    }
}
