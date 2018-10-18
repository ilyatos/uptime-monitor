<?php

namespace App\Database;

use Core\Traits\DBConnectionTrait;

class CreateTables
{
    use DBConnectionTrait;

    public static function run()
    {
        self::createServicesTable();
        self::createReasonsTable();
        self::createResponsesTable();
    }

    public static function createServicesTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS services (
                id INT NOT NULL AUTO_INCREMENT,
                alias VARCHAR (255),
                url VARCHAR (255) NOT NULL UNIQUE,
                token VARCHAR (255) NOT NULL UNIQUE,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        return self::getConnection()->exec($sql);
    }

    public static function createReasonsTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS reasons (
                id INT NOT NULL AUTO_INCREMENT,
                reason VARCHAR (255) NOT NULL UNIQUE,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        return self::getConnection()->exec($sql);
    }

    public static function createResponsesTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS responses (
                id INT NOT NULL AUTO_INCREMENT,
                service_id INT NOT NULL,
                response_size FLOAT COMMENT 'Bytes',
                response_time TIME(3),
                availability TINYINT (1),
                reason_id INT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
                FOREIGN KEY (reason_id) REFERENCES reasons(id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        return self::getConnection()->exec($sql);
    }
}