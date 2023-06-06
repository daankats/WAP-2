<?php

namespace app\core\Middlewares;

use app\core\Auth;
use app\core\exception\ForbiddenException;
use app\core\Request;
use app\core\Response;

class AuthMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Response $response)
    {
        if (Auth::isGuest()) {
            // Gebruiker is niet ingelogd, doorverwijzen naar de inlogpagina
            $response->redirect('/login');
            return $response;
        }

        // Voer de volgende middleware of controller actie uit
        return null;
    }
}
