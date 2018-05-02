<?php
namespace App\Controllers;

class CommentController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index(){
        echo $this->app->load('twig')->render('admin/comment/index.twig');
    }
    
    public function add()
    {
    
    }
    
    public function update($id){
    
    }
    
    public function delete($id){
    
    }
    
    public function getCommentById($id){
        
    }
}