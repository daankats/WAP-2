<?php

namespace app\database;

class DbConnection
{
    private static ?DatabaseConnection $dbConnection = null;

    public static function getConnection(): DatabaseConnection
    {
        if (self::$dbConnection === null) {
            self::$dbConnection = Database::create();
        }

        return self::$dbConnection;
    }
}