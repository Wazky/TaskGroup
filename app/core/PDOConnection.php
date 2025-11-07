<?php
// file: /app/core/PDOConnection.php

class PDOConnection {
    private static $dbhost = "127.0.0.1"; // Database host
    private static $dbname = "taskgroup"; // Database name
    private static $dbuser = "tguser"; // Database user
    private static $dbpass = "tgpass"; // Database password
    private static $db_singleton = null;

    public static function getInstance() {
        if (self::$db_singleton == null) {
            self::$db_singleton = new PDO(
                "mysql:host=".self::$dbhost.";dbname=".self::$dbname.";charset=utf8", // connection string
                self::$dbuser, // username
                self::$dbpass, // password
                array( // options
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        }
        return self::$db_singleton;
    }
}
?>