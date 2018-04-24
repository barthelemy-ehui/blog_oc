<?php
namespace App\Auths;

class Session
{
    
    public function get($sessionName) {
        return $_SESSION[$sessionName];
    }
    
    public function set($sessionName, $sessionValue) {
        $_SESSION[$sessionName] = $sessionValue;
    }
    
    public function has($sessionName) {
        return isset($_SESSION[$sessionName]);
    }
    
    public function clear($sessionName) {
        unset($_SESSION[$sessionName]);
    }
}