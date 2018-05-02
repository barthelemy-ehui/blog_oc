<?php
namespace App\Controllers;

class PostController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index(){
        echo $this->app->load('twig')->render('admin/post/index.twig');
    }
    
    public function add(){
    
    }
    
    public function update(){
    
    }
    
    public function delete(){
    
    }
    
    public function getPostBySlug($slug){
    
    }
    
    public function getPostById($id){
    
    }
}