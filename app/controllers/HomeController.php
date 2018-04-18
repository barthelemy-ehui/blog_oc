<?php

namespace App\Controllers;


use App\App;

class HomeController extends Controller
{
    
    public function index($data)
    {
        var_dump($data);
        echo 'index';
     
    }
    
    public function show(){
        echo 'show';
    }
    
    public function inscription($name){

        echo $this->app->load('twig')->render('index.twig',['the'=>'logo']);
    }
}