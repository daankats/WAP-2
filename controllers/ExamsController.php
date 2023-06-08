<?php

namespace app\controllers;

use app\core\App;
use app\core\Auth;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\CourseModel;
use app\models\EnrollmentModel;
use app\models\ExamsModel;
use app\models\UserModel;
use app\core\middlewares\ExamsMiddleware;
use app\models\GradesModel;
use app\models\RegisterModel;

class ExamsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new ExamsMiddleware(['index', 'create', 'edit', 'delete', 'update', 'view', 'grade', 'updategrade', 'addgrade', 'addGrades']));
    }

    public function index()
    {
        $exams = ExamsModel::findAllObjects();
        $courses = CourseModel::findAllObjects();
        $users = UserModel::findAllObjects();
        $user = UserModel::findOne(['id' => App::$app->user->id]);
        $enrollments = EnrollmentModel::findAllObjects();
        $enrolled = false;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->user_id == $user->id) {
                $enrolled = true;
            }
        }

        $this->view->title = 'Exams';
        $this->view->render('/exams/index', [
            'exams' => $exams,
            'courses' => $courses,
            'users' => $users,
            'user' => $user,
            'enrolled' => $enrolled,
            'app' => App::$app,
        ], 'auth');
    }

    public function create(Request $request, Response $response)
    {
        $exam = new ExamsModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        if (Auth::isTeacher() || Auth::isAdmin()) {
            if ($request->isPost()) {
                $exam->loadData($request->getBody());
                $exam->course_id = $_POST['course_id'];
                $exam->created_by = $user->id;
                $exam->created_at = date('Y-m-d H:i:s');

                if ($exam->validate() && $exam->save()) {
                    App::$app->session->setFlash('success', 'Examen succesvol aangemaakt.');
                    $response->redirect('/exams');
                    return;
                } else {
                    App::$app->session->setFlash('error', 'Er is een fout opgetreden bij het opslaan van het examen.');
                }
            }

            $this->view->title = 'Examen aanmaken';
            $this->view->render('/exams/create', [
                'model' => $exam,
            ], 'auth');
            return;
        }

        $response->redirect('/exams');
    }

    public function edit(Request $request)
    {
        $id = $request->getQueryParams()['id'];
        $exam = ExamsModel::findOne(['id' => $id]);

        if ($exam === null) {
            $exception = new \Exception("Examen niet gevonden.");
            $this->view->render('/_error', [], $exception);
            return;
        }

        $this->view->render('/exams/edit', [
            'model' => $exam,
        ], 'auth');
    }

    public function update(Request $request, Response $response)
    {
        $id = $request->getQueryParams()['id'] ?? null;

        if ($id === null) {
            echo "id is null";
            return;
        }

        $exam = ExamsModel::findOne(['id' => $id]);

        if ($exam === null) {
            $exception = new \Exception("Examen niet gevonden.");
            $this->view->render('/_error', ['exception' => $exception]);
            return;
        }

        $exam->loadData($request->getBody());

        if ($exam->validate() && $exam->update()) {
            App::$app->session->setFlash('success', 'Examen succesvol bijgewerkt.');
            $response->redirect('/exams');
        } else {
            $exception = new \Exception("Kan het examen niet bijwerken.");
            $this->view->render('/_error', ['exception' => $exception]);
        }
    }

    public function registerExam()
    {
        $register = new RegisterModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        if ($user == Auth::isStudent()) {
            $register->student_id = $user->id;

            if ($register->loadData($_POST) && $register->validate()) {
                $register->exam_id = $_POST['exam_id'];
                $register->student_id = $user->id;

                if ($register->save()) {
                    App::$app->session->setFlash('success', 'Je hebt je succesvol ingeschreven voor het examen.');
                    header('Location: /exams');
                    exit;
                } else {
                    App::$app->session->setFlash('error', 'Er is een fout opgetreden bij het opslaan van je examenregistratie.');

                    return 'Er is een fout opgetreden bij het opslaan van je examenregistratie.';
                }
            } else {
                App::$app->session->setFlash('error', 'Er is een fout opgetreden bij je examenregistratieverzoek.');

                return 'Er is een fout opgetreden bij je examenregistratieverzoek.';
            }
        } else {
            App::$app->session->setFlash('error', 'Je moet een student zijn om je voor een examen in te schrijven.');

            return 'Je moet een student zijn om je voor een examen in te schrijven.';
        }
    }

    public function unregisterExam()
    {
        $register = RegisterModel::findOne(['exam_id' => $_POST['exam_id'], 'student_id' => App::$app->user->id]);

        if ($register) {
            if ($register->delete()) {
                App::$app->session->setFlash('success', 'Je hebt je succesvol uitgeschreven voor het examen.');
                header('Location: /exams');
                exit;
            }
        }

        App::$app->session->setFlash('error', 'Er is een fout opgetreden bij je uitschrijvingsverzoek.');

        $this->view->render('/_error', [
            'message' => 'Er is een fout opgetreden bij je uitschrijvingsverzoek.'
        ], 'auth');
    }

    public function delete(Request $request, Response $response)
    {
        $exam = ExamsModel::findOne(['id' => $request->getBody()['id']]);

        if (!$exam) {
            $response->setStatusCode(404);
            $this->view->render('/error/404');
            return;
        }

        if (Auth::isTeacher() || Auth::isAdmin()) {
            $exam->delete();
            App::$app->session->setFlash('success', 'Examen succesvol verwijderd.');
            $response->redirect('/exams');
        } else {
            App::$app->session->setFlash('error', 'Je hebt geen toestemming om dit examen te verwijderen.');
            $response->redirect('/exams');
        }
    }

    public function addGrades(Request $request, Response $response)
    {
        $grade = new GradesModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        if ($request->isPost()) {
            $exam_id = $_POST['exam_id'] ?? null;
            $grade_value = $_POST['grade'] ?? null;
            $student_id = $_POST['student_id'] ?? null;

            $grade->user_id = $student_id;
            $grade->exam_id = $exam_id;
            $grade->grade = $grade_value;

            if ($grade->loadData($request->getBody()) && $grade->validate()) {
                $grade->user_id = $student_id;
                $grade->exam_id = $exam_id;
                $grade->grade = $grade_value;
                $grade->save();

                App::$app->session->setFlash('success', 'Beoordeling succesvol toegevoegd');
            } else {
                App::$app->session->setFlash('error', 'Kan de beoordeling niet toevoegen');
            }

            header('Location: /exams/addgrades?id=' . $exam_id);
            exit;
        } else {
            $exams = GradesModel::findAllObjects();
            $this->view->title = 'Beoordelingen toevoegen';
            $this->view->render('/exams/addgrades', [
                'exams' => $exams,
                'user' => $user,
            ], 'auth');
        }
    }

    public function showGrades()
    {
        $user = UserModel::findOne(['id' => App::$app->user->id]);
        $exams = new Examsmodel();
        $exams = ExamsModel::findAllByUserId($user->id);
        $this->view->title = 'Examenuitslagen';
        $this->view->render('/exams/results', [
            'exams' => $exams,
            'user' => $user,
        ], 'auth');
    }

    public function updateGrade(Request $request, Response $response)
    {
        $grade = new GradesModel();
        $grade = GradesModel::findOne(['id' => $request->getBody()['id']]);
        var_dump($_POST);

        if (!$grade) {
            throw new \Exception('Beoordeling niet gevonden');
        }

        $grade->loadData($request->getBody());

        if ($grade->validate()) {
            $grade->update();
            App::$app->session->setFlash('success', 'Beoordeling succesvol bijgewerkt');
        } else {
            App::$app->session->setFlash('error', 'Kan de beoordeling niet bijwerken');
        }

        header('Location: /exams/addgrades?id=' . $grade->exam_id);
        exit;
    }
}
