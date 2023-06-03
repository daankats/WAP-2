<?php

namespace app\core\db;

use FFI\Exception;
use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = $this->getEnv('DB_HOST');
        $port = $this->getEnv('DB_PORT');
        $database = $this->getEnv('DB_DATABASE');
        $username = $this->getEnv('DB_USERNAME');
        $password = $this->getEnv('DB_PASSWORD');

        try {
            $this->pdo = $this->createPDO($host, $port, $database, $username, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }

    private function getEnv($key)
    {
        $envFilePath = __DIR__ . '/../../.env';
        $envFile = fopen($envFilePath, 'r');

        if (!$envFile) {
            throw new Exception('Unable to open .env file.');
        }

        while (($line = fgets($envFile)) !== false) {
            $line = trim($line);
            if ($line && substr($line, 0, 1) !== '#') {
                list($name, $value) = explode('=', $line, 2);
                if ($name === $key) {
                    return $value;
                }
            }
        }

        return null;
    }
    private function createPDO($host, $port, $database, $username, $password)
    {
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $username, $password, $options);
    }
}
