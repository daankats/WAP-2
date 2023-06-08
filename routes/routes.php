<?php

use app\controllers\HomeController;
use app\controllers\AuthController;
use app\controllers\AdminController;
use app\controllers\CourseController;
use app\controllers\ProfileController;
use app\controllers\ExamsController;
use app\controllers\ProgressController;
use app\controllers\DashboardController;
use app\core\Middlewares\AuthMiddleware;

// SITE
$app->router->get('/', [HomeController::class, 'index']);
$app->router->get('/dashboard', [DashboardController::class, 'index'], AuthMiddleware::class);
// AUTH
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [ProfileController::class, 'index'], AuthMiddleware::class);

// ADMIN
$app->router->get('/admin', [AdminController::class, 'index'], AuthMiddleware::class);
$app->router->get('/admin/edit', [AdminController::class, 'edit'], AuthMiddleware::class);
$app->router->post('/admin/delete', [AdminController::class, 'delete'], AuthMiddleware::class);

// COURSES
$app->router->get('/courses', [CourseController::class, 'index']);
$app->router->get('/courses/create', [CourseController::class, 'create'], AuthMiddleware::class);
$app->router->post('/courses/create', [CourseController::class, 'create'], AuthMiddleware::class);
$app->router->get('/courses/enroll', [CourseController::class, 'enroll']);
$app->router->post('/courses/enroll', [CourseController::class, 'enroll']);
$app->router->get('/courses/leave', [CourseController::class, 'unenroll']);
$app->router->post('/courses/leave', [CourseController::class, 'unenroll']);
$app->router->get('/courses/edit', [CourseController::class, 'edit'], AuthMiddleware::class);
$app->router->post('/courses/update', [CourseController::class, 'updateCourse'], AuthMiddleware::class);
$app->router->get('/courses/delete', [CourseController::class, 'delete'], AuthMiddleware::class);
$app->router->post('/courses/delete', [CourseController::class, 'delete'], AuthMiddleware::class);

// EXAMS
$app->router->get('/exams', [ExamsController::class, 'index']);
$app->router->get('/exams/create', [ExamsController::class, 'create'], AuthMiddleware::class);
$app->router->post('/exams/create', [ExamsController::class, 'create'], AuthMiddleware::class);
$app->router->get('/exams/edit', [ExamsController::class, 'update'], AuthMiddleware::class);
$app->router->post('/exams/update', [ExamsController::class, 'update'], AuthMiddleware::class);
$app->router->get('/exams/delete', [ExamsController::class, 'delete'], AuthMiddleware::class);
$app->router->post('/exams/delete', [ExamsController::class, 'delete'], AuthMiddleware::class);

// REGISTER EXAM AND GRADES
$app->router->get('/exams/registerexam', [ExamsController::class, 'registerExam'], AuthMiddleware::class);
$app->router->post('/exams/registerexam', [ExamsController::class, 'registerExam'], AuthMiddleware::class);
$app->router->get('/exams/unregisterexam', [ExamsController::class, 'unregisterExam'], AuthMiddleware::class);
$app->router->post('/exams/unregisterexam', [ExamsController::class, 'unregisterExam'], AuthMiddleware::class);
$app->router->get('/exams/addgrades', [ExamsController::class, 'addGrades'], AuthMiddleware::class);
$app->router->post('/exams/addgrades', [ExamsController::class, 'addGrades'], AuthMiddleware::class);
$app->router->get('/exams/updategrade', [ExamsController::class, 'updateGrade'], AuthMiddleware::class);
$app->router->post('/exams/updategrade', [ExamsController::class, 'updateGrade'], AuthMiddleware::class);

// GRADES FOR TEACHERS
$app->router->get('/exams/results', [ExamsController::class, 'showGrades'], AuthMiddleware::class);
$app->router->post('/exams/results', [ExamsController::class, 'showGrades'], AuthMiddleware::class);

// MY PROGRESS FOR STUDENTS
$app->router->get('/myprogress', [ProgressController::class, 'myProgress'], AuthMiddleware::class);
