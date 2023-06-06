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

class ExamsController extends Controller {

    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new ExamsMiddleware(['index','create', 'edit', 'delete', 'update', 'view', 'grade', 'updategrade', 'addgrade', 'addGrades']));
    }
    
    public function index() {
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
    
    public function create(Request $request, Response $response) {
        $exam = new ExamsModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);
        if (Auth::isTeacher()|| Auth::isAdmin()) {
            if ($request->isPost()) {
                $exam->loadData($request->getBody());
                $exam->course_id = $_POST['course_id'];
                $exam->created_by = $user->id;
                $exam->created_at = date('Y-m-d H:i:s');
                if ($exam->validate() && $exam->save()) {
                    $response->redirect('/exams');
                    exit;
                }
            }
            $this->view->title = 'Create Exam';
            $this->view->render('/exams/create', [
                'model' => $exam,
            ], 'auth');
        }
        $response->redirect('/exams');
    }
    
    public function update(Request $request, Response $response)
    {
        $id = $_GET['id'];
        $exam = ExamsModel::findOne(['id' => $id]);

        if ($exam !== null && $request->isPost()) {
            $exam->loadData($_POST);
            if ($exam->validate() && $exam->update()) {
                $response->redirect('/exams');
                return;
            } 
        }
        $this->view->title = 'Update Exam';
        $this->view->render('/exams/edit', [
            'model' => $exam,
        ], 'auth');
    }

    public function registerExam()
    {
        $register = new RegisterModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);
        
        if ($user == !Auth::isStudent()) {
            $register->student_id = $user->id;
            if ($register->loadData($_POST) && $register->validate()) {
                $register->exam_id = $_POST['exam_id'];
                $register->student_id = $user->id;
                
                if ($register->save()) {
                    App::$app->session->setFlash('success', 'You have successfully registered for the exam.');
                    header('Location: /exams');
                    exit;
                } else {
                    App::$app->session->setFlash('error', 'There was an error saving your exam registration.');

                    return 'There was an error saving your exam registration.';
                }
            } else {
                App::$app->session->setFlash('error', 'There was an error with your exam registration request.');

                return 'There was an error with your exam registration request.';
            }
        } else {
            App::$app->session->setFlash('error', 'You must be a student to register for an exam.');

            return 'You must be a student to register for an exam.';
        }
    }
    
    
    public function unregisterExam()
    {
        $register = RegisterModel::findOne(['exam_id' => $_POST['exam_id'], 'student_id' => App::$app->user->id]);
        if ($register) {
            if ($register->delete()) {
                App::$app->session->setFlash('success', 'You have successfully unregistered from the exam.');
                header('Location: /exams');
                exit;
            }
        }
        App::$app->session->setFlash('error', 'There was an error with your unenrollment request.');

        $this->view->render('/_error', [
            'message' => 'There was an error with your unenrollment request.'
        ], 'auth');
    }
    
    public function delete(Request $request, Response $response)
    {
        $exam = ExamsModel::findOne(['id' => $request->getBody()['id']]);
        if (!$exam) {
            $response->setStatusCode(404);
            $this->view->render('/error/404');
        }
        if (Auth::isTeacher() || Auth::isAdmin()) {
            $exam->delete();
            $response->redirect('/exams');
        }
        $response->redirect('/exams');
    }

    public function addGrades(Request $request, Response $response)
    {   
        $grade = new GradesModel();
        $user = UserModel::findOne(['id' => App::$app->user->id]);
        
        if ($request->isPost()) {
            $exam_id = $_POST['exam_id'] ?? null;
            $grade_value = $_POST['grade'] ?? null;
            $student_id = $_POST['student_id'] ?? null;;

            $grade->user_id = $student_id;
            $grade->exam_id = $exam_id;
            $grade->grade = $grade_value;
            
            if ($grade->loadData($request->getBody()) && $grade->validate()) {
                $grade->user_id = $student_id;
                $grade->exam_id = $exam_id;
                $grade->grade = $grade_value;
                $grade->save();
                
                App::$app->session->setFlash('success', 'Grade added successfully');
            } else {
                
                App::$app->session->setFlash('error', 'Failed to add grade');
            }
            
            header('Location: /exams/addgrades?id=' . $exam_id);
            exit;
        } else {
            $exams = GradesModel::findAllObjects();
            $this->view->title = 'Add Grades';
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
        $this->view->title = 'Exam Results';
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
            throw new \Exception('Grade not found');
        }
        
        $grade->loadData($request->getBody());
        
        if ($grade->validate()) {
            $grade->update();
            App::$app->session->setFlash('success', 'Grade updated successfully');
        } else {
            App::$app->session->setFlash('error', 'Failed to update grade');
        }
        
        header('Location: /exams/addgrades?id=' . $grade->exam_id);
        exit;
    }


}
