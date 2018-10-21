<?php

namespace Core\Traits;

use PDO;

trait DBConnectionTrait
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
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                $connection = new PDO(
                    sprintf("mysql:dbname=%s; host=%s; charset=utf8", getenv('DB_NAME'), getenv('DB_HOST')),
                    getenv('DB_USER'), getenv('DB_PASSWORD'), $options);
            } catch (\PDOException $e) {
                echo $e->getMessage();
                die;
            }
        }

        return $connection;
    }
}
