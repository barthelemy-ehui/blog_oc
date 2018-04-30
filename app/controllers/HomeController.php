<?php

namespace App\Controllers;

class HomeController extends Controller
{
    
    /**
     * http_method=get
     */
    public function index()
    {
        
        echo $this->app->load('twig')->render('front/index.twig');
    }
}
