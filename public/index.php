<?php   

require_once __DIR__.'/../vendor/autoload.php';

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\controllers\AdminController;
use app\controllers\CourseController;
use app\controllers\ProfileController;
use app\controllers\ExamsController;

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
$app->router->get('/profile', [ProfileController::class, 'index']);

// ADMIN
$app->router->get('/admin', [AdminController::class, 'index']);
$app->router->get('/admin/edit', [AdminController::class, 'edit']);
$app->router->post('/admin/delete', [AdminController::class, 'delete']);


// COURSES
$app->router->get('/courses', [CourseController::class, 'index']);
$app->router->get('/courses/create', [CourseController::class, 'create']);
$app->router->post('/courses/create', [CourseController::class, 'create']);
$app->router->get('/courses/enroll', [CourseController::class, 'enroll']);
$app->router->post('/courses/enroll', [CourseController::class, 'enroll']);
$app->router->get('/courses/leave', [CourseController::class, 'leave']);
$app->router->post('/courses/leave', [CourseController::class, 'leave']);
$app->router->get('/courses/edit', [CourseController::class, 'update']);
$app->router->post('/courses/edit', [CourseController::class, 'update']);
$app->router->get('/courses/delete', [CourseController::class, 'delete']);
$app->router->post('/courses/delete', [CourseController::class, 'delete']);

// EXAMS
$app->router->get('/exams', [ExamsController::class, 'index']);
$app->router->get('/exams/create', [ExamsController::class, 'create']);
$app->router->post('/exams/create', [ExamsController::class, 'create']);
$app->router->get('/exams/edit', [ExamsController::class, 'update']);
$app->router->post('/exams/edit', [ExamsController::class, 'update']);
$app->router->get('/exams/delete', [ExamsController::class, 'delete']);
$app->router->post('/exams/delete', [ExamsController::class, 'delete']);

// REGISTER EXAM
$app->router->get('/registerexam', [ExamsController::class, 'registerexam']);
$app->router->post('/registerexam', [ExamsController::class, 'registerexam']);





$app->run();