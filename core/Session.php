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

    // In Session.php
    public function getSessionUser()
    {
        return $this->get('user');
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * @param string $key
     */
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
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }

    public function setFlash(string $string, string $string1): void
    {
        $_SESSION['flash'][$string] = $string1;
    }
}
