<?php

namespace App\Controllers;

class HomeController
{
    private $db;
    
    public function __construct()
    {
    }
    
    public function index($data)
    {
        var_dump($data);
        echo 'index';
    }
    
    public function show(){
        echo 'show';
    }
}