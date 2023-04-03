<?php  

require_once __DIR__.'/vendor/autoload.php';

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\core\App;
use app\core\Database;

// add database config
$config = new Database();

$app = new App(__DIR__, $config);

$app->db->applyMigrations();