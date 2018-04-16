<?php

namespace App\controllers;


class TestController
{
    
    /**
     * http_method=get
     */
    public function index(){
        echo 'index method';
    }
    
    /**
     * http_method=get
     */
    public function create(){
        echo 'create method';
    }
    
    /**
     * http_method=delete
     */
    public function remove(){
        echo 'remove method';
    }
    
    /**
     * http_method=get
     */
    public function show($value){
        var_dump($value);
        echo 'show method';
    }
}