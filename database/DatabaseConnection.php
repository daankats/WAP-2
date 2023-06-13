<?php

namespace app\database;

use PDO;

interface DatabaseConnection
{
    public function getPdo(): PDO;
}
