<?php
namespace App\Controllers;

class CommentController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index() {
        echo $this->app->load('twig')->render('admin/comment/index.twig');
    }
    
    public function store(){
        // valider
    
        // stocker dans la base
    
        //redirection vers index
    }
    
    
    public function update($id){
        // valider
    
        //stocker dans la base
    
        //redirection vers index
    }
    
    public function delete($id) {
        
        // supprimer
        
        // redirection vers index
    }
    
    public function getCommentById($id){
        
    }
}