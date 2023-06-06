<?php

namespace app\controllers;

class HomeController
{
    public function index()
    {
        $view = new \app\core\View();
        $view->render('home');
    }
}
