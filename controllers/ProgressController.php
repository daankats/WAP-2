<?php

namespace app\controllers;

use app\core\Controller;
use app\models\GradesModel;
use app\models\ExamsModel;
use app\models\User;
use app\core\App;

class ProgressController extends Controller
{
    public function myProgress()
    {
        $user = User::findOne(['id' => App::$app->user->id]);

        $gradesModel = new GradesModel();
        $grades = $gradesModel->findAll();

        $exams = [];
        foreach ($grades as $grade) {
            $exam = ExamsModel::findOne(['id' => $grade->exam_id]);
            if ($exam) {
                $exams[$grade->exam_id] = $exam;
            }
        }

        return $this->render('myprogress', [
            'grades' => $grades,
            'exams' => $exams,
            'user' => $user,
        ]);
    }
}
