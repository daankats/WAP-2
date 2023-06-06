<?php

use app\controllers\HomeController;
use app\controllers\AuthController;
use app\controllers\AdminController;
use app\controllers\CourseController;
use app\controllers\ProfileController;
use app\controllers\ExamsController;
use app\controllers\ProgressController;

// SITE
$app->router->get('/', [HomeController::class, 'index']);

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
$app->router->post('/courses/create', [CourseController::class, 'store']);
$app->router->get('/courses/enroll', [CourseController::class, 'enroll']);
$app->router->post('/courses/enroll', [CourseController::class, 'enroll']);
$app->router->get('/courses/leave', [CourseController::class, 'unenroll']);
$app->router->post('/courses/leave', [CourseController::class, 'unenroll']);
$app->router->get('/courses/edit', [CourseController::class, 'edit']);
$app->router->post('/courses/update', [CourseController::class, 'update']);
$app->router->get('/courses/delete', [CourseController::class, 'delete']);
$app->router->post('/courses/delete', [CourseController::class, 'delete']);

// EXAMS
$app->router->get('/exams', [ExamsController::class, 'index']);
$app->router->get('/exams/create', [ExamsController::class, 'create']);
$app->router->post('/exams/create', [ExamsController::class, 'store']);
$app->router->get('/exams/edit', [ExamsController::class, 'edit']);
$app->router->post('/exams/update', [ExamsController::class, 'update']);
$app->router->get('/exams/delete', [ExamsController::class, 'delete']);
$app->router->post('/exams/delete', [ExamsController::class, 'delete']);

// REGISTER EXAM AND GRADES
$app->router->get('/exams/registerexam', [ExamsController::class, 'registerExam']);
$app->router->post('/exams/registerexam', [ExamsController::class, 'storeRegisteredExam']);
$app->router->get('/exams/unregisterexam', [ExamsController::class, 'unregisterExam']);
$app->router->post('/exams/unregisterexam', [ExamsController::class, 'storeUnregisteredExam']);
$app->router->get('/exams/addgrades', [ExamsController::class, 'addGrades']);
$app->router->post('/exams/addgrades', [ExamsController::class, 'storeGrades']);
$app->router->get('/exams/updategrade', [ExamsController::class, 'updateGrade']);
$app->router->post('/exams/updategrade', [ExamsController::class, 'storeUpdatedGrade']);

// GRADES FOR TEACHERS
$app->router->get('/exams/results', [ExamsController::class, 'showGrades']);
$app->router->post('/exams/results', [ExamsController::class, 'showGrades']);

// MY PROGRESS FOR STUDENTS
$app->router->get('/myprogress', [ProgressController::class, 'myProgress']);
