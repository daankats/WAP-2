<?php

namespace app\controllers;

use app\core\View;

class HomeController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home',
            'content' => View::render('home', [], false)
        ];

        return View::render('layouts/main', $data);
    }
}
