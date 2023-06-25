<?php

use app\controllers\AdminController;
use app\controllers\AuthController;
use app\controllers\CourseController;
use app\controllers\DashboardController;
use app\controllers\ExamsController;
use app\controllers\HomeController;
use app\controllers\ProfileController;
use app\controllers\ProgressController;
use app\core\middlewares\AdminMiddleware;
use app\core\middlewares\AuthMiddleware;
use app\core\middlewares\CourseMiddleware;
use app\core\middlewares\ExamsMiddleware;

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
$app->router->get('/admin', [AdminController::class, 'index'], AdminMiddleware::class);
$app->router->get('/admin/edit', [AdminController::class, 'edit'], AdminMiddleware::class);
$app->router->post('/admin/update', [AdminController::class, 'updateUser'], AdminMiddleware::class);
$app->router->post('/admin/delete', [AdminController::class, 'delete'], AdminMiddleware::class);

// COURSES
$app->router->get('/courses', [CourseController::class, 'index'], CourseMiddleware::class);
$app->router->get('/courses/create', [CourseController::class, 'create'], CourseMiddleware::class);
$app->router->post('/courses/create', [CourseController::class, 'create'], CourseMiddleware::class);
$app->router->get('/courses/enroll', [CourseController::class, 'enroll'], CourseMiddleware::class);
$app->router->post('/courses/enroll', [CourseController::class, 'enroll'], CourseMiddleware::class);
$app->router->get('/courses/leave', [CourseController::class, 'unenroll'], CourseMiddleware::class);
$app->router->post('/courses/leave', [CourseController::class, 'unenroll'], CourseMiddleware::class);
$app->router->get('/courses/edit', [CourseController::class, 'edit'], CourseMiddleware::class);
$app->router->post('/courses/update', [CourseController::class, 'updateCourse'], CourseMiddleware::class);
$app->router->get('/courses/delete', [CourseController::class, 'delete'], CourseMiddleware::class);
$app->router->post('/courses/delete', [CourseController::class, 'delete'], CourseMiddleware::class);

// EXAMS
$app->router->get('/exams', [ExamsController::class, 'index']);
$app->router->get('/exams/create', [ExamsController::class, 'create'], ExamsMiddleware::class);
$app->router->post('/exams/create', [ExamsController::class, 'create'], ExamsMiddleware::class);
$app->router->get('/exams/edit', [ExamsController::class, 'edit'], ExamsMiddleware::class);
$app->router->post('/exams/update', [ExamsController::class, 'update'], ExamsMiddleware::class);
$app->router->get('/exams/delete', [ExamsController::class, 'delete'], ExamsMiddleware::class);
$app->router->post('/exams/delete', [ExamsController::class, 'delete'], ExamsMiddleware::class);

// REGISTER EXAM AND GRADES
$app->router->get('/exams/registerexam', [ExamsController::class, 'registerExam'], ExamsMiddleware::class);
$app->router->post('/exams/registerexam', [ExamsController::class, 'registerExam'], ExamsMiddleware::class);
$app->router->get('/exams/unregisterexam', [ExamsController::class, 'unregisterExam'], ExamsMiddleware::class);
$app->router->post('/exams/unregisterexam', [ExamsController::class, 'unregisterExam'], ExamsMiddleware::class);
$app->router->get('/exams/addgrades', [ExamsController::class, 'addGrades'], ExamsMiddleware::class);
$app->router->post('/exams/addgrades', [ExamsController::class, 'addGrades'], ExamsMiddleware::class);
$app->router->get('/exams/updategrade', [ExamsController::class, 'updateGrade'], ExamsMiddleware::class);
$app->router->post('/exams/updategrade', [ExamsController::class, 'updateGrade'], ExamsMiddleware::class);

// GRADES FOR TEACHERS
$app->router->get('/exams/results', [ExamsController::class, 'showGrades'], ExamsMiddleware::class);
$app->router->post('/exams/results', [ExamsController::class, 'showGrades'], ExamsMiddleware::class);

// MY PROGRESS FOR STUDENTS
$app->router->get('/myprogress', [ProgressController::class, 'myProgress'], AuthMiddleware::class);

