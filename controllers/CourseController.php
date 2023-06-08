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
use app\models\ExamsModel;

class CourseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new CourseMiddleware(['create', 'edit', 'update', 'delete']));
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
                        App::$app->session->setFlash('success', 'Cursus succesvol aangemaakt.');
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
        ], 'auth');
    }


    public function edit(Request $request)
    {
        $id = $request->getQueryParams()['id'];
        $course = CourseModel::findOne(['id' => $id]);

        if ($course === null) {
            $exception = new \Exception("Cursus niet gevonden.");
            $this->view->render('/_error', [], $exception);
            return;
        }

        $this->view->render('/courses/edit', [
            'model' => $course,
        ], 'auth');
    }

    public function updateCourse(Request $request, Response $response)
    {
        $id = $request->getQueryParams()['id'] ?? null;
        if ($id === null) {
            // Foutbehandeling, bijvoorbeeld:
            echo "id is null";
            return;
        }

        $course = CourseModel::findOne(['id' => $id]);

        if ($course === null) {
            $exception = new \Exception("Cursus niet gevonden.");
            $this->view->render('/_error', ['exception' => $exception]);
            return;
        }

        $course->loadData($request->getBody());

        if ($course->validate() && $course->update()) {
            App::$app->session->setFlash('success', 'Cursus succesvol bijgewerkt.');
            $response->redirect('/courses');
        } else {
            $exception = new \Exception("Het bijwerken van de cursus is mislukt.");
            $this->view->render('/_error', ['exception' => $exception]);
        }
    }

    public function delete(Request $request, Response $response)
    {
        $courseId = $request->getBody()['id'];
        $course = CourseModel::findOne(['id' => $courseId]);

        if (Auth::isTeacher() || Auth::isAdmin()) {
            $associatedExam = ExamsModel::findOne(['course_id' => $courseId]);

            if ($associatedExam) {
                App::$app->session->setFlash('error', 'Er is een examen gekoppeld aan deze cursus. Verwijder eerst het examen.');
            } else {
                if ($course->delete()) {
                    App::$app->session->setFlash('success', 'Cursus succesvol verwijderd.');
                    $response->redirect('/courses');
                    return;
                } else {
                    App::$app->session->setFlash('error', 'Verwijderen mislukt. Probeer het opnieuw.');
                }
            }
        } else {
            App::$app->session->setFlash('error', 'Je hebt geen toegang tot deze pagina.');
        }
        $response->redirect('/courses');
    }

    public function enroll(Request $request, Response $response)
    {
        $courseId = $request->getBody()['course_id'];
        $enrollment = new EnrollmentModel();
        $enrollment->student_id = App::$app->user->id;
        $enrollment->course_id = $courseId;

        if ($enrollment->save()) {
            App::$app->session->setFlash('success', 'Je bent succesvol ingeschreven voor de cursus!');
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
            App::$app->session->setFlash('success', 'Je bent succesvol uitgeschreven voor de cursus!');
            $response->redirect('/courses');
        } else {
            $response->setStatusCode(500);
        }
    }
}
