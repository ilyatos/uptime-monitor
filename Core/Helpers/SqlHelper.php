<?php

namespace Core\Helpers;

use Core\Traits\DBConnectionTrait;

class SqlHelper
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

    public function where(array $whereStmt): SqlHelper
    {
        $column = key($whereStmt);
        $value = $whereStmt[$column];

        $newSql = rtrim($this->sql) . " WHERE $column='$value'";

        return new SqlHelper($newSql);
    }

    public function limit(int $limit): SqlHelper
    {
        $newSql = rtrim($this->sql) . " LIMIT $limit";

        return new SqlHelper($newSql);
    }

    public function fetch()
    {
        $this->execute();
        return $this->query->fetch();
    }

    public function execute(): bool
    {
        $connection = self::getConnection();

        $this->query = $connection->prepare($this->sql);

        return $this->query->execute();
    }

    public function fetchAll()
    {
        $this->execute();
        return $this->query->fetchAll();
    }
}