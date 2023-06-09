<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\models\ExamsModel;
use app\models\GradesModel;
use app\models\UserModel;

class ProgressController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function myProgress()
    {
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        $gradesModel = new GradesModel();
        $grades = $gradesModel->findById($user->id);

        $exams = [];
        foreach ($grades as $grade) {
            $exam = ExamsModel::findOne(['id' => $grade->exam_id]);
            if ($exam) {
                $exams[$grade->exam_id] = $exam;
            }
        }

        return $this->view->render('myprogress', [
            'grades' => $grades,
            'exams' => $exams,
            'user' => $user,
        ], 'auth');
    }
}
