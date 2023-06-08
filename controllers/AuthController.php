<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\View;
use app\models\UserModel;
use app\models\LoginModel;
use app\core\middlewares\AuthMiddleware;
use app\core\Auth;
use app\core\App;


class AuthController extends Controller
{
    protected Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware(['profile', 'register', 'logout']));
    }

    public function login(Request $request): View
    {
        $loginModel = new LoginModel();
        $errorMessage = '';

        if ($request->isPost()) {
            $loginModel->loadData($request->getBody());

            if ($loginModel->validate() && $loginModel->login()) {
                return $this->redirect('/dashboard');
            } else {
                $errorMessage = 'Invalid credentials. Please try again.';
            }
        }

        $this->view->title = 'Login'; // Set the title
        $this->view->render('login', [
            'model' => $loginModel,
            'errorMessage' => $errorMessage,
        ]);

        return $this->view;
    }

    public function register(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->loadData($request->getBody());

        if ($request->isPost()) {
            $isValidationSuccessful = $user->validate();

            if ($isValidationSuccessful && $user->register()) {
                App::$app->session->setFlash('success', 'Account created successfully.');
                return $response->redirect('/dashboard');
            } else {
                App::$app->session->setFlash('error', 'Er is een fout opgetreden. Probeer het opnieuw.');
            }
        }

        return $this->view->render('register', [
            'model' => $user
        ], 'auth');
    }



    public function logout()
    {
        App::$app->user = null;
        App::$app->session->remove('user');
        return $this->redirect('/login');
    }

}
