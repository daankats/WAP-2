<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\App;
use app\core\Request;
use app\core\Response;
use app\core\Router;

$app = new App(dirname(__DIR__));

// Create request and response objects
$request = new Request();
$response = new Response();

// Create router object
$router = new Router($request, $response);

// Include routes file
require_once __DIR__ . '/../routes/routes.php';

// Resolve the request
$content = $router->resolve();
$response->setContent($content);
$response->send();
