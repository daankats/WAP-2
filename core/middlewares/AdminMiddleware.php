<?php

namespace app\core\middlewares;

use app\core\Auth;
use app\core\exception\ForbiddenException;
use app\core\Request;
use app\core\Response;

class AdminMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Response $response)
    {
        if (Auth::isGuest() || !Auth::isAdmin()) {
            // Gebruiker is niet ingelogd, doorverwijzen naar de inlogpagina
            throw new ForbiddenException();
        }

        // Voer de volgende middleware of controller actie uit
        return null;
    }
}
