<?php

namespace app\core\middlewares;

use app\core\App;
use app\core\exception\ForbiddenException;

class CourseMiddleware extends BaseMiddleware
{
    public function execute()
    {
        $request = App::$app->request;
        $uri = $request->getUri();

        // Check if the user is a student and is trying to access the courses index page
        if ($uri === '/courses' || $uri === '/courses/enroll' || $uri === '/courses/leave' && App::isStudent()) {
            return;
        }

        // If the user is not a student or is trying to access a different page, throw a ForbiddenException
        throw new ForbiddenException();
    }
}
