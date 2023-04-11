<?php
namespace app\core\middlewares;
use app\core\App;
use app\core\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware {

    public array $actions = [];

    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    public function execute()
{
    $controller = App::$app->controller;
    if ($controller->action === 'logout') {
        return;
    }
    
    if (App::isGuest()) {
        if (empty($this->actions) || in_array($controller->action, $this->actions)) {
            throw new ForbiddenException();
        }
    } else {
        if (!App::isAdmin()) {
            throw new ForbiddenException();
        }
    }
}

}