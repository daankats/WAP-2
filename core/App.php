<?php

namespace app\core;

use app\core\db\Database;
use app\core\Auth;
use app\models\UserModel;


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
    public ?UserModel $user = null;
    public string $userClass = UserModel::class;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this);
        $this->db = new Database();
        $this->userClass = UserModel::class;

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


}
