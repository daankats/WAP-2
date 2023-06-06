<?php

namespace app\controllers;

use app\core\App;
use app\core\Auth;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\middlewares\CourseMiddleware;
use app\models\CourseModel;
use app\models\EnrollmentModel;
use app\models\UserModel;

class CourseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new CourseMiddleware(['create', 'store', 'edit', 'update', 'delete']));
    }

    public function index()
    {
        $courses = CourseModel::findAllObjects();
        $enrollments = EnrollmentModel::findAllObjects();
        $users = UserModel::findAllObjects();
        $user = UserModel::findOne(['id' => App::$app->user->id]);
        $enrolled = false;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->user_id == $user->id) {
                $enrolled = true;
            }
        }

        $this->view->title = 'Cursussen';
        $this->view->render('/courses/index', [
            'courses' => $courses,
            'enrollments' => $enrollments,
            'users' => $users,
            'user' => $user,
            'enrolled' => $enrolled,
            'app' => App::$app,
        ], 'auth');
    }

    public function create(Request $request, Response $response)
    {
        $course = new CourseModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        if ($request->isPost()) {
            if (Auth::isTeacher() || Auth::isAdmin()) {
                if ($course->loadData($request->getBody())) {
                    $course->created_by = $user->id;
                    $course->created_at = date('Y-m-d H:i:s');
                    if ($course->validate() && $course->save()) {
                        $response->redirect('/courses');
                        return;
                    }
                }
                $this->view->render('/courses/create', [
                    'model' => $course,
                ]);
                return;
            } elseif (Auth::isStudent()) {
                $response->redirect('/courses');
                return;
            } else {
                $this->view->render('/forbidden');
                return;
            }
        }

        $this->view->render('/courses/create', [
            'model' => $course,
        ]);
    }

    public function update(Request $request, Response $response)
    {
        $id = $request->getQueryParams()['id'];
        $course = CourseModel::findOne(['id' => $id]);

        if ($course !== null && $request->isPost()) {
            $course->loadData($request->getBody());
            if ($course->validate() && $course->update()) {
                $response->redirect('/courses');
                return;
            } else {
                $exception = new \Exception("Failed to update the course.");
                $this->view->render('/_error', [], $exception);
                return;
            }
        }

        $this->view->render('/courses/edit', [
            'model' => $course,
        ]);
    }

    public function delete(Request $request, Response $response)
    {
        $courseId = $request->getBody()['id'];
        $course = CourseModel::findOne(['id' => $courseId]);

        if (Auth::isTeacher() || Auth::isAdmin()) {
            if ($course->delete()) {
                $response->redirect('/courses');
                return;
            }
        }

        $this->view->render('/courses/delete', [
            'model' => $course,
        ]);
    }

    public function enroll(Request $request, Response $response)
    {
        $courseId = $request->getBody()['course_id'];
        $enrollment = new EnrollmentModel();
        $enrollment->student_id = App::$app->user->id;
        $enrollment->course_id = $courseId;

        if ($enrollment->save()) {
            App::$app->session->setFlash('success', 'You have successfully enrolled in the course!');
            $response->redirect('/courses');
        } else {
            $response->setStatusCode(500);
        }
    }

    public function unenroll(Request $request, Response $response)
    {
        $courseId = $request->getBody()['course_id'];
        $enrollment = EnrollmentModel::findOne(['course_id' => $courseId, 'student_id' => App::$app->user->id]);

        if ($enrollment->delete()) {
            App::$app->session->setFlash('success', 'You have successfully unenrolled from the course!');
            $response->redirect('/courses');
        } else {
            $response->setStatusCode(500);
        }
    }
}
