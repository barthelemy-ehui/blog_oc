<?php
namespace App\routes;


final class RouteSingleton
{
    private static $instance = null;
    
    public static function getInstance()
    {
        if(is_null(static::$instance)){
            static::$instance = new Route();
        }
        
        return static::$instance;
    }
}