<?php

namespace app\core\db;
use Exception;
use PDO;
use app\core\App;

/**
 * Summary of Database
 */
class Database{
    public PDO $pdo;

    private function customgetenv($key) {
        $envFilePath = __DIR__ . '/../../.env'; // Path to your .env file
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

        $this->pdo = new PDO('mysql:host=' . $this->customgetenv('DB_HOST') . ';port=' . $this->customgetenv('DB_PORT') . ';dbname=' . $this->customgetenv('DB_DATABASE'), $this->customgetenv('DB_USERNAME'), $this->customgetenv('DB_PASSWORD'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function applyMigrations(){
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(App::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach($toApplyMigrations as $migration){
            if($migration === '.' || $migration === '..'){
                continue;
            }
            require_once App::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationsTable(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations(){{
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
}

public function saveMigrations($migrations){
    $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
    $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
    $str");
    $statement->execute();
}

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

    protected function log($message){
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }


}