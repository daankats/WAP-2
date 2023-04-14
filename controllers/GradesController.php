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
use app\models\GradesModel;
use app\models\RegisterModel;

class GradesController extends Controller {
    
    public function __construct()
    {
       
    }
    
    public function index()
{
    $user = User::findOne(['id' => App::$app->user->id]);
    $grades = GradesModel::findAllObjects();
    $courses = CourseModel::findAllObjects();
    $users = User::findAllObjects();
    $registers = EnrollmentModel::findAllObjects();
    $registered = false;
    $exams = RegisterModel::findAllObjects();
    foreach ($registers as $register) {
        if ($register->user_id == $user->id) {
            $registered = true;
        }
    }
    $this->layout = 'main';
    return $this->render('/grades/index', [
        'grades' => $grades,
        'courses' => $courses,
        'users' => $users,
        'user' => $user,
        'registered' => $registered,
        'app' => App::$app,
        'exams' => $exams,
        'isAdmin' => App::isAdmin(),
        'isDocent' => App::isDocent(),
    ]);
}

    
    public function create(Request $request, Response $response) {
        $grade = new GradesModel();
        $user = User::findOne(['id' => App::$app->user->id]);
        if (App::isDocent() || App::isAdmin()) {
            if ($request->isPost()) {
                $grade->loadData($request->getBody());
                if ($grade->validate() && $grade->save()) {
                    $response->redirect('/grades');
                    exit;
                }
            }
            return $this->render('/grades/create', [
                'model' => $grade,
                'user' => $user,
            ]);
        }
        $response->redirect('/grades');
        exit;
    }
    
    public function edit(Request $request, Response $response) {
        $grade = GradesModel::findOne(['id' => $request->getBody()['id']]);
        $user = User::findOne(['id' => App::$app->user->id]);
        if (App::isDocent() || App::isAdmin()) {
            if ($request->isPost()) {
                $grade->loadData($request->getBody());
                $grade->updated_by = $user->id;
                $grade->updated_at = date('Y-m-d H:i:s');
                if ($grade->validate() && $grade->save()) {
                    $response->redirect('/grades');
                    exit;
                }
            }
            return $this->render('/grades/edit', [
                'model' => $grade,
                'user' => $user,
            ]);
        }
        $response->redirect('/grades');
        exit;
    }

}