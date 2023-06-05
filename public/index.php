<?php

use app\core\App;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App(dirname(__DIR__));

// Include routes file
require_once __DIR__ . '/../routes/routes.php';


// Start de applicatie
$app->run();
