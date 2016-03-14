<?php
require_once 'vendor/autoload.php';


define('ROOT', __DIR__);
define('PUBLIC_FOLDER', ROOT . DIRECTORY_SEPARATOR . 'public');


$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$verticalOps = new \App\VerticalOps();
$verticalOps->fire();


