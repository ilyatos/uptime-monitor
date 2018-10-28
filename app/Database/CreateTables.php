<?php

namespace App\Database;

use Core\Traits\DBConnectionTrait;

class CreateTables
{
    use DBConnectionTrait;

    /**
     * Run the creation of given tables.
     */
    public static function run()
    {
        self::createServicesTable();
        self::createReasonsTable();
        self::createResponsesTable();
    }

    /**
     * Creates the `services` table.
     *
     * @return bool
     */
    public static function createServicesTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS services (
                id INT NOT NULL AUTO_INCREMENT,
                alias VARCHAR (255),
                url VARCHAR (255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        return self::getConnection()->exec($sql);
    }

    /**
     * Creates the `reasons` table.
     *
     * @return bool
     */
    public static function createReasonsTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS reasons (
                id INT NOT NULL AUTO_INCREMENT,
                reason VARCHAR (255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        return self::getConnection()->exec($sql);
    }

    /**
     * Creates the `responses` table.
     *
     * @return bool
     */
    public static function createResponsesTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS responses (
                id INT NOT NULL AUTO_INCREMENT,
                service_id INT NOT NULL,
                response_size INT(5) COMMENT 'Bytes',
                response_time FLOAT(5,3),
                availability TINYINT (1),
                reason_id INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
                FOREIGN KEY (reason_id) REFERENCES reasons(id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        return self::getConnection()->exec($sql);
    }
}
