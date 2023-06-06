<?php

use app\core\App;
use app\core\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$request = new Request();
$rootDir = dirname(__DIR__);

$app = new App($rootDir, $request);

require_once __DIR__ . '/../routes/routes.php';

$app->run();
