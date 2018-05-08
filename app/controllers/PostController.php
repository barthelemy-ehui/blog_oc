<?php
namespace App\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index(){
        echo $this->app->load('twig')->render('admin/post/index.twig');
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function add(){
        echo $this->app->load('twig')->render('admin/post/add.twig',[
            'Post' => Post::class
        ]);
    }
    
    public function store(){
        
        // valider
        
        // stocker dans la base
        
        // redirection vers index
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function edit($slug){
        $datas = [
            'slug' => '',
            'title' => '',
            'description' => '',
            'content' => '',
            'status' => '',
            'publish_at' => ''
        ];
        
        echo $this->app->load('twig')->render('admin/post/edit.twig', [
            'datas' => $datas,
            'Post' => Post::class
        ]);
    }
    
    public function update(){
        // valider
        
        //stocker dans la base
        
        //redirection vers index
    }
    
    public function delete($id){
        // supprimer
        
        // redirection vers index
    }
    
    public function getPostBySlug($slug){
    
    }
    
    public function getPostById($id) {
    
    }
}