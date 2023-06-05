<?php

namespace app\core\db;
use Exception;
use PDO;

class Database{
    public PDO $pdo;

    private function customgetenv($key) {
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
    public function __construct(){
        try {
        $this->pdo = new PDO('mysql:host=' . $this->customgetenv('DB_HOST') . ';port=' . $this->customgetenv('DB_PORT') . ';dbname=' . $this->customgetenv('DB_DATABASE'), $this->customgetenv('DB_USERNAME'), $this->customgetenv('DB_PASSWORD'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }



}