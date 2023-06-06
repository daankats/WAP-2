<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\models\UserModel;
use app\core\App;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        return $this->render('profile', [
            'user' => $user,
        ]);
    }
}
