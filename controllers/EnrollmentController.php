<?php

namespace app\controllers;


use app\core\Controller;
use app\core\middlewares\EnrollmentMiddleware;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new EnrollmentMiddleware(['enroll']));
    }
}
