<?php

namespace app\core;

use app\core\App;
use db\Database;
use db\DbModel;
use app\models\UserModel;

class Response {
    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function redirect(string $url) {
        header('Location: ' . $url);
        exit;
    }

    public function setContent($content) {
        echo $content;
    }

    public function send() {
        ob_end_flush();
    }
}

