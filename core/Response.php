<?php

namespace app\core;

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
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
        exit;
    }

}

