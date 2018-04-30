<?php
namespace App\Controllers;


class AdminController extends Controller
{
    public function index() {
        echo $this->app->load('twig')->render('admin/index.twig');
    }
    
    /**
     * auth=admin
     */
    public function users(){
        
        $users =  $this->app
            ->load('repoManager')
            ->getInstance('UserRepository')
            ->getAll();
        
        echo $this->app->load('twig')->render('admin/users.twig',[
            'users' => $users
        ]);
    }
}
