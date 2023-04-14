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

        if ($uri === '/exams' || $uri === '/exams/registerexam' || $uri === '/exams/unregisterexam' && App::isStudent()) {
            return;
        }
        elseif(App::isAdmin() || App::isDocent()){
            return;
        }

        throw new ForbiddenException();
    }
}
