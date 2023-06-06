<?php

namespace app\controllers;

use app\core\Auth;
use app\core\Controller;
use app\core\Middlewares\AuthMiddleware;

class DashboardController extends Controller
{
    protected Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware('index'));
    }
    public function index()
    {
        $this->view->title = 'Dashboard';
        $this->view->render('dashboard', [], 'auth');
    }
}

