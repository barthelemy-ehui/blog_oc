<?php
namespace App\Routes;

use App\App;

final class RouteSingleton
{
    private static $instance = null;
    
    public static function getInstance(App $app)
    {
        if(is_null(static::$instance)){
            static::$instance = new Route($app);
        }
        
        return static::$instance;
    }
}