<?php

namespace Monitor\Database;

use Core\Traits\DBConnectionTrait;

class CreateTable
{
    use DBConnectionTrait;

    public static function run()
    {
        self::createServicesTable();
    }

    public static function createServicesTable()
    {
        $sql = /** @lang SQL */ "
            CREATE TABLE IF NOT EXISTS services (
                id INT NOT NULL AUTO_INCREMENT,
                alias VARCHAR (255),
                url VARCHAR (255) NOT NULL UNIQUE,
                response_size FLOAT,
                response_time TIME,
                availability TINYINT (1),
                reason VARCHAR (255),
                PRIMARY KEY (id)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";

        self::connection()->exec($sql);
    }
}