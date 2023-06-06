<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Middlewares\AuthMiddleware;
use app\core\Request;
use app\models\UserModel;
use app\core\App;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware(['index']));
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
