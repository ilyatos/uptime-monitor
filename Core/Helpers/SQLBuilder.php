<?php

namespace Core\Helpers;

use Core\Traits\DBConnectionTrait;

class SQLBuilder
{
    use DBConnectionTrait;

    /** @var string */
    private $sql;
    /** @var \PDOStatement */
    private $query;

    public function __construct(string $sql)
    {
        $this->sql = $sql;
    }

    /**
     * Add where clause to a sql.
     *
     * @param array $whereStmt
     * @return SQLBuilder
     */
    public function where(array $whereStmt): SQLBuilder
    {
        $column = key($whereStmt);
        $value = $whereStmt[$column];

        $newSql = rtrim($this->sql) . " WHERE $column='$value'";

        return new SQLBuilder($newSql);
    }

    /**
     * Add LIMIt %limit to a sql.
     *
     * @param int $limit
     * @return SQLBuilder
     */
    public function limit(int $limit): SQLBuilder
    {
        $newSql = rtrim($this->sql) . " LIMIT $limit";

        return new SQLBuilder($newSql);
    }

    /**
     * Execute a sql query.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $connection = self::getConnection();

        $this->query = $connection->prepare($this->sql);

        return $this->query->execute();
    }

    /**
     * Return one record.
     *
     * @return array
     */
    public function fetch(): array
    {
        $this->execute();
        return $this->query->fetch();
    }

    /**
     * Return all records.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        $this->execute();
        return $this->query->fetchAll();
    }
}