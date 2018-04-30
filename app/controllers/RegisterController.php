<?php
namespace App\controllers;

use App\Auths\Auth;

class RegisterController extends Controller
{
    
    /**
     * http_method=get
     */
    public function index() {
        echo $this->app->load('twig')->render('admin/auth/register.twig');
    }
    
    /**
     * http_method=get
     */
    public function connect() {
        echo $this->app->load('twig')->render('admin/auth/connect.twig');
    }
    
    public function store() {
        // todo : stocker les identifiants
    }
    
    /**
     * http_method=post
     */
    public function login()
    {
        $stmt = $this->app->load('auth')->login($_POST['email'], $_POST['password']);
        if($this->app->load('auth')->login($_POST['email'], $_POST['password'])){
            header("Location: /home/connected");
        }
        
        //todo: erreur
        echo 'error out';
    }
    
    /**
     * http_method=get
     */
    public function logout()
    {
        if($this->app->load('session')->has(Auth::UserAuthentifiedKeySession)){
            $this->app->load('session')->clear(Auth::UserAuthentifiedKeySession);
        }
        echo 'You have been logged out';
    }
}