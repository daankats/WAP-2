<?php

namespace app\core;

use app\core\Controller;
use app\models\User;
use app\core\Request;
use app\core\Response;
use app\core\db\Database;
use app\core\Session;
use app\core\db\DbModel;

class App {
    public static string $ROOT_DIR;
    public static App $app;
    public string $layout = 'main';
    public string $userClass = '';
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public ?User $user = null;
    public View $view;

    public function __construct($rootPath) {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->db = new Database();
        $this->userClass = User::class;
    
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $userInstance = new $this->userClass();
            $primaryKey = $userInstance->primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }
    }

    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function getController() {
        return $this->controller;
    }

    public function setController(Controller $controller) {
        $this->controller = $controller;
    }

    public function login(DbModel $user) {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest(): bool
    {
        return self::$app->user === null;
    }

    public static function isAdmin(): bool
    {
        return self::$app->user->role === 'beheerder';
    }

}

