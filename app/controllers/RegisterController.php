<?php
namespace App\controllers;


class RegisterController extends Controller
{
    
    /**
     * http_method=post
     * auth=admin
     */
    public function register(){
        // register
        // address email, password, first_name, last_name
        
        echo 'register';
    }
    
    public function connect(){
    
    }
    
    /**
     * http_method=post
     */
    public function login()
    {
        //todo, quand l'utilisateur se connect
        echo 'login';
        
    }
    
    /**
     * http_method=get
     */
    public function logout()
    {
        //todo, quand l'utilisateur se déconnect
        echo 'logout';
    }
}