<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;

class ExamsMiddleware extends BaseMiddleware
{
   
    public function execute()
    {
        $request = App::$app->request;
        $uri = $request->getUri();

        // Check if the user is a student and is trying to access the courses index page
        if ($uri === '/exams' || $uri === '/exams/registerexam' || $uri === '/exams/unregisterexam' && App::isStudent()) {
            return;
        }
        elseif(App::isAdmin() || App::isDocent()){
            return;
        }

        // If the user is not a student or is trying to access a different page, throw a ForbiddenException
        throw new ForbiddenException();
    }
}
