<?php
namespace App\controllers;


class RegisterController extends Controller
{
    
    /**
     * http_method=post
     * auth=admin
     */
    public function register(){
        echo 'register';
    }
    
    public function connect(){
        //
    }
    
    public function store(){
        //
    }
    
    /**
     * http_method=post
     */
    public function login()
    {
        echo 'login';
        
    }
    
    /**
     * http_method=get
     */
    public function logout()
    {
        echo 'logout';
    }
}
