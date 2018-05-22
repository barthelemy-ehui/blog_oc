<?php
namespace App\controllers;

use App\App;

abstract class Controller
{
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    
    protected function redirect($location): void
    {
        header('Location: ' . $location);
    }
}