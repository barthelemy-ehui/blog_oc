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
        $latestThreePosts = $this->app->load('repoManager')
        ->getInstance('PostRepository')
        ->getTheTreeLatestPosts();
        
        echo $this->app->load('twig')->render('front/index.twig',[
            'posts' => $latestThreePosts
        ]);
    }
}