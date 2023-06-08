<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view->title = 'Home';
        if (App::$app->user) {
            $this->view->render('home', [], 'auth');
        } else {
            $this->view->render('home', [], 'main');
        }
       
    }
}

