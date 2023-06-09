<?php

namespace app\core;

class Session
{

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function getSessionUser()
    {
        return $this->get('user');
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }


    public function get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }


    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        session_write_close();
    }

    public function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $messages = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $messages;
        }
        return null;
    }

    public function setFlash(string $key, string $message): void
    {
        if (!isset($_SESSION['flash'][$key])) {
            $_SESSION['flash'][$key] = [];
        }
        $_SESSION['flash'][$key][] = $message;
    }
}
