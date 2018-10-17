<?php

namespace Core\Database;

use Core\Traits\DBConnectionTrait;

class CreateTable
{
    use DBConnectionTrait;

    /** @var \PDO */
    private $connection;

    public function run()
    {
        $this->connection = self::getConnection();
        $this->createServicesTable();
    }

    public function createServicesTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS services (
                id INT NOT NULL AUTO_INCREMENT, 
                url VARCHAR (255) NOT NULL UNIQUE,
                response_size FLOAT,
                response_time TIME,
                availability TINYINT (1),
                reason VARCHAR (255),
                PRIMARY KEY (id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        $this->connection->exec($sql);
    }
}