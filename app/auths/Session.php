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
    
    public function set($sessionName, $sessionValue): void
    {
        $_SESSION[$sessionName] = serialize($sessionValue);
    }
    
    public function has($sessionName): bool
    {
        return isset($_SESSION[$sessionName]);
    }
    
    public function clear($sessionName): void
    {
        unset($_SESSION[$sessionName]);
    }
}