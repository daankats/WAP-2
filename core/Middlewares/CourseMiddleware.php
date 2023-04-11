<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;

class CourseMiddleware extends BaseMiddleware
{
    public function execute()
    {
       if (App::isDocent() || App::isAdmin()) {
           return true;
       }elseif(App::isStudent()) {
              return true;
       }else{
        throw new ForbiddenException();
       }
    }
}
