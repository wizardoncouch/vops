<?php


namespace App\Factory;


class DB extends \PDO
{
//    protected static $instance;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        $dbDriver = getenv('DB_DRIVER');
        $dbHost = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');

        $dsn = $dbDriver . ':host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8';
        $usr = getenv('DB_USER');
        $pwd = getenv('DB_PASSWORD');
        parent::__construct($dsn, $usr, $pwd);
    }

//    public static function __callStatic($name, $arguments)
//    {
//        if( !self::$instance ) {
//            self::$instance = new self();
//        }
//
//        return self::$pdo->{$name}($arguments);
//    }
}