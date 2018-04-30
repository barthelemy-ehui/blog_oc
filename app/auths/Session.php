<?php
namespace App\Auths;

use App\Models\User;

class Session
{
    
    public function get($sessionName) {
        $class = $_SESSION[$sessionName];
        return unserialize($class, [
            'allowed_classes'=> [
                User::class
            ]
        ]);
    }
    
    public function set($sessionName, $sessionValue) {
        $_SESSION[$sessionName] = serialize($sessionValue);
    }
    
    public function has($sessionName) {
        return isset($_SESSION[$sessionName]);
    }
    
    public function clear($sessionName) {
        
        unset($_SESSION[$sessionName]);
    }
}
