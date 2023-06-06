<?php

use app\core\App;
use app\core\Request;
use app\core\Response;
use app\core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$request = new Request();
$response = new Response();
$rootDir = dirname(__DIR__);

$app = new App($rootDir, $request, $response);
$app->router = new Router($app); // Initialiseer de Router en wijs deze toe aan de router-eigenschap van de App-instantie

require_once __DIR__ . '/../routes/routes.php';

$app->run();
