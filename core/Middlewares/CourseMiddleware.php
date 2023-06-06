<?php

namespace app\core\Middlewares;

use app\core\App;
use app\core\Auth;
use app\core\exception\ForbiddenException;


class CourseMiddleware extends BaseMiddleware
{
    public function handle($request, $response)
    {
        $uri = $request->getUri();

        if (Auth::isGuest()) {
            // Gebruiker is niet ingelogd, doorverwijzen naar de inlogpagina
            $response->redirect('/login');
            return;
        }

        if (($uri === '/courses' || $uri === '/courses/enroll' || $uri === '/courses/leave') && Auth::isStudent()) {
            // Gebruiker is student en heeft toegang tot de specifieke routes
            return;
        } elseif (Auth::isAdmin() || Auth::isTeacher()) {
            // Gebruiker is admin of docent en heeft toegang tot alle routes
            return;
        }

        throw new ForbiddenException();
    }
}

