<?php

namespace app\core;

use app\core\db\Database;
use app\core\db\DbModel;
use app\models\User;

class App
{
    public static string $ROOT_DIR;
    public static App $app;
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public ?User $user = null;
    public string $userClass = User::class;
    public View $view;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
        $this->userClass = User::class;
        $this->view = new View();

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $userInstance = new $this->userClass();
            $primaryKey = $userInstance->primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }
    }

    public function run()
    {
        try {
            $this->router->resolve($this->request);
        } catch (\Exception $e) {
            $this->response->setStatusCode((int)$e->getCode());
            $this->response->setContent($e->getMessage());
        }

        $this->response->send();
    }
    public function isGuest()
    {
        return !$this->user;
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}
