<?php 

namespace app\core;

class Session {
    
    protected const FLASH_KEY = 'flash_messages';

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $storedFlashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($storedFlashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $storedFlashMessages;
    }

    /**
     * @param string $key
     * @param mixed $message
     */
    public function setFlash(string $key, $message): void {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getFlash(string $key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key] ?? false;
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }

    public function __destruct() {
        $storedFlashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($storedFlashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($storedFlashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $storedFlashMessages;
    }
}
