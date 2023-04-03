<?php

namespace app\core;

class App {

    public static string $ROOT_DIR;
    public static App $app;
    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;
    public Database $db;


    public function __construct($rootPath, $config) {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        // test conntection:
        $this->db = new Database();
    }

    public function run() {
        echo $this->router->resolve();
    }

    public function getController() {
        return $this->controller;
    }

    public function setController(Controller $controller) {
        $this->controller = $controller;
    }


}