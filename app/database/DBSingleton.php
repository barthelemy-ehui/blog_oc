<?php

final class DBSingleton
{
    /**
     * @var DBSingleton
     */
    private static $instance;
    
    
    public static function getInstance() : PDO
    {
        if(null === static::$instance) {
            $config = require __DIR__.'/../../config/DbConfig.php';
            try{
                static::$instance = new PDO($config['dsn'],$config['username'], $config['password']);
            }catch(Exception $e){
                 printf('Something wrong has been happened : %s', $e->getMessage());
            }
        }
        
        return static::$instance;
    }
    
    private function __construct()
    {

    }
    
    private function __clone()
    {
    }
    
    private function __wakeup()
    {
    }
}
