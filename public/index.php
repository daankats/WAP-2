<?php

use app\core\App;
use app\core\Request;
use app\core\Response;
use app\core\Router;
use app\core\Session;
use app\models\UserModel;

require_once __DIR__ . '/../vendor/autoload.php';

$request = new Request();
$response = new Response();
$router = new Router();
$session = new Session();

// Inlogproces
$user = null;
$primaryValue = $session->getSessionUser();
if ($primaryValue) {
    $userInstance = new UserModel();
    $primaryKey = $userInstance->primaryKey();
    $user = UserModel::findOne([$primaryKey => $primaryValue]);
}

$app = new App($router, $request, $response, $session, $user);

require_once __DIR__ . '/../routes/routes.php';

$app->run();
