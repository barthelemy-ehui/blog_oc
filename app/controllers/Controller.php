<?php
namespace App\Controllers;

use App\App;

abstract class Controller
{
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    
    protected function redirect($location) {
        header('Location: ' . $location);
    }
}
