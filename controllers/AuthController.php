<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\models\LoginModel;
use app\core\middlewares\AuthMiddleware;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }
    public function login(Request $request)
    {
        $loginModel = new LoginModel();
        if ($request->isPost()) {
            $loginModel->loadData($request->getBody());
            if ($loginModel->validate() && $loginModel->login()) {
                App::$app->response->redirect('/');
                exit;
            }
        }
        $this->layout = 'auth';
        return $this->render('login', 
        [
            'model' => $loginModel
        ]);
    }

    public function register(Request $request)
    {
        $User = new User();
        if ($request->isPost()) {
            $User->loadData($request->getBody());
            if ($User->validate() && $User->register()) {
                App::$app->session->setFlash('success', 'Bedankt voor het registreren!');
                App::$app->response->redirect('/');
                exit;
            }
        }
    
        $this->layout = 'auth';
        return $this->render('register', [
            'model' => $User
        ]);
    }

    public function logout()
    {
        App::$app->logout();
        App::$app->response->redirect('/');
    } 

    public function profile()
    {
        
        return $this->render('profile');
    }
    

}
