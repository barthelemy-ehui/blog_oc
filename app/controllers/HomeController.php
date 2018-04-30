<?php

namespace App\Controllers;


use App\App;
use App\Models\User;
use App\Auths\Auth;

class HomeController extends Controller
{
    
    /**
     * http_method=get
     */
    public function index()
    {
        echo $this->app->load('twig')->render('front/index.twig');
    }
    
    /**
     *http_method=get
     *auth=admin
     */
    public function connected(){
       $user = $this->app->load('session')->get(Auth::UserAuthentifiedKeySession);
       echo $this->app->load('twig')->render('index.twig',[
           'user' => $user
       ]);
    }
}