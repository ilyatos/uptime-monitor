<?php

namespace Core\Traits;

use PDO;

trait ConnectionTrait
{

    /**
     * Get the PDO database connection.
     *
     * @return PDO
     */
    protected static function getConnection()
    {
        static $connection;

        if ($connection === null) {

            $config = require ROOT . '/Core/config/db.php';

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ];

            try {
                $connection = new PDO(
                    sprintf("mysql:dbname=%s; host=%s; charset=utf8", $config['name'], $config['host']),
                    $config['user'],
                    $config['password'],
                    $options);
            } catch (\PDOException $e) {
                echo $e->getMessage();
                die;
            }
        }

        return $connection;
    }
}
