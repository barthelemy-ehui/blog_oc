<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 18/04/2018
 * Time: 21:02
 */

namespace App\controllers;

use App\App;

abstract class Controller
{
    protected $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
}