<?php
define("USER", "root");
define("PASS", "");
define("DB", "rf");
define("HOST", "localhost");

class Database
{
    public static function StartUp()
    {
        $pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DB . ';charset=utf8', USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}