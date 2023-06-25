<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\models\UserModel;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $user = UserModel::findOne(['id' => App::$app->user->id]);

        $this->view->title = 'Profiel';
        return $this->view->render('profile', [
            'user' => $user,
        ], 'auth');
    }
}
