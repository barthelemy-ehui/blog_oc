<?php
namespace App\Controllers;


class AdminController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index() {
        
        $articlesCount = $this->app->load('repoManager')
        ->getInstance('PostRepository')
        ->getCount();
        
        $commentsCount = $this->app->load('repoManager')
        ->getInstance('CommentRepository')
        ->getCount();
        
        echo $this->app->load('twig')->render('admin/index.twig',[
            'commentsCount' => $commentsCount,
            'articlesCount' => $articlesCount
        ]);
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function users(){
        $users =  $this->app
            ->load('repoManager')
            ->getInstance('UserRepository')
            ->getAll();
        
        echo $this->app->load('twig')->render('admin/user/index.twig',[
            'users' => $users
        ]);
    }
}
