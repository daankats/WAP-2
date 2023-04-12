<?php 

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\CourseModel;
use app\models\EnrollmentModel;
use app\models\ExamsModel;
use app\models\User;
use app\core\middlewares\ExamsMiddleware;

class ExamsController extends Controller {

    public function __construct()
    {
        $this->registerMiddleware(new ExamsMiddleware(['create', 'edit', 'delete']));
    }
    
    public function index() {
        $exams = ExamsModel::findAllObjects();
        $courses = CourseModel::findAllObjects();
        $users = User::findAllObjects();
        $user = User::findOne(['id' => App::$app->user->id]);
        $enrollments = EnrollmentModel::findAllObjects();
        $enrolled = false;
        foreach ($enrollments as $enrollment) {
            if ($enrollment->user_id == $user->id) {
                $enrolled = true;
            }
        }
        $this->layout = 'main';
        return $this->render('/exams/index', [
            'exams' => $exams,
            'courses' => $courses,
            'users' => $users,
            'user' => $user,
            'enrolled' => $enrolled,
            'app' => App::$app,
        ]);
    }
    
    public function create(Request $request, Response $response) {
        $exam = new ExamsModel();
        $user = User::findOne(['id' => App::$app->user->id]);
        if (App::isDocent() || App::isAdmin()) {
            if ($request->isPost()) {
                $exam->loadData($request->getBody());
                $exam->created_by = $user->id;
                $exam->created_at = date('Y-m-d H:i:s');
                if ($exam->validate() && $exam->save()) {
                    $response->redirect('/exams');
                    exit;
                }
            }
            return $this->render('/exams/create', [
                'model' => $exam,
            ]);
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
        return $this->render('/exams/edit', [
            'model' => $exam,
        ]);
    }

    public function delete(Request $request, Response $response)
    {
        $exam = ExamsModel::findOne(['id' => $request->getBody()['id']]);
        if (!$exam) {
            $response->setStatusCode(404);
            return $this->render('/error/404');
        }
        if (App::isDocent() || App::isAdmin()) {
            $exam->delete();
            $response->redirect('/exams');
        }
        $response->redirect('/exams');
    }

}
