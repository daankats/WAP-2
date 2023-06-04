<?php

namespace app\controllers;

use app\core\View;

class HomeController
{
    public function index()
    {
        // Render the "home" view
        return View::render('home');
    }
}


