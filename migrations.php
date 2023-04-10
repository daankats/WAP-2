<?php  

require_once __DIR__.'/vendor/autoload.php';
use app\core\App;
use app\core\db\Database;

// add database config
$config = new Database();

$app = new App(__DIR__);

$app->db->applyMigrations();