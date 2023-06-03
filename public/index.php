<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\core\App;

$app = new App(dirname(__DIR__));

// Laad de routes
require_once __DIR__ . '/../routes/routes.php';

$app->run();
