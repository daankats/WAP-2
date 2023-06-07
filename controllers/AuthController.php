<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
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

    public function register(Request $request)
    {
        $user = new UserModel();
        $this->view->title = 'Register';
        $this->view->render('register', [
            'model' => $user
        ], 'main');

        return $this->view;
    }

    public function registerPost(Request $request)
    {
        $user = new UserModel();

        if ($request->getMethod() === 'POST') {
            var_dump($request->getBody());
            $user->loadData($request->getBody());
            var_dump($user);
            if ($user->validate() && $user->register()) {
                var_dump($user);
                App::$app->session->setFlash('success', 'Registratie succesvol. U kunt nu inloggen.');
                return $this->redirect('/dashboard');
            } else {
                App::$app->session->setFlash('error', 'Er zijn fouten opgetreden bij de registratie. Controleer uw gegevens en probeer het opnieuw.');
            }
        }

        return $this->redirect('/register');
    }



    public function logout()
    {
        $this->auth->logout();
        return $this->redirect('/');
    }

    public function profile(Request $request)
    {
        $this->view->title = 'Profiel';
        $this->view->render('profile');

        return $this->view;
    }
}
