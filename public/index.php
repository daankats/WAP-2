<?php   

require_once __DIR__.'/../vendor/autoload.php';

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\controllers\AdminController;

use app\core\App;

$app = new App(dirname(__DIR__));


// SITE
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

// AUTH
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [AuthController::class, 'profile']);

// ADMIN
$app->router->get('/admin', [AdminController::class, 'index']);
$app->router->get('/admin/edit', [AdminController::class, 'edit']);
$app->router->post('/admin/delete', [AdminController::class, 'delete']);



$app->run();