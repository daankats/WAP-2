<?php

namespace app\controllers;

use app\core\Auth;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;

class DashboardController extends Controller
{
    protected Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware());
    }

    public function index()
    {
        $this->view->title = 'Dashboard';
        $this->view->render('dashboard', [], 'auth');
    }
}
