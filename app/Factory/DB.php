<?php


namespace App\Factory;


class DB extends \PDO
{

    /**
     * DB constructor.
     */
    public function __construct()
    {
        try {
            $dbDriver = getenv('DB_DRIVER');
            $dbHost = getenv('DB_HOST');
            $dbName = getenv('DB_NAME');

            $dsn = $dbDriver . ':host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8';
            $usr = getenv('DB_USER');
            $pwd = getenv('DB_PASSWORD');
            parent::__construct($dsn, $usr, $pwd);
        } catch (\PDOException $e) {
            header('HTTP/1.0 410 Gone');
            die($e->getMessage());
        } catch (\Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            die($e->getMessage());
        }
    }

}