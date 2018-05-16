<?php
namespace App\Controllers;

use App\Validation\Validator;

class CommentController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index() {
        
        $comments = $this->app->load('repoManager')
            ->getInstance('CommentRepository')
            ->getAll();
        
        echo $this->app->load('twig')->render('admin/comment/index.twig', [
            'comments' => $comments
        ]);
    }
    
    /**
     * http_method=post
     */
    public function store(){
        
        $validator = new Validator();
        $validator->addRule([
           'title' => Validator::REQUIRED,
           'content' => Validator::REQUIRED,
           'email' => Validator::REQUIRED,
           'post_id' => Validator::REQUIRED,
           'slug' => Validator::REQUIRED,
        ]);
    
        $data = $validator->validate();
        $errors = $validator->getErrors();
        
        if(count($errors['errors'])>0) {
            $this->app->load('session')
                 ->set(Validator::class, $errors);
            $this->redirect('/admin/posts/getPostBySlug/' + $errors['datas']['slug']);
            return;
        }
        $this->redirect('/admin/posts/getPostBySlug/' + $data['slug']);
    }
    
    
    /**
     * http_method=post
     * auth=admin
     */
    public function update(){
        
        $data['status'] = $_POST['status'];
        $data['id'] = $_POST['id'];
        
        $this->app->load('repoManager')
            ->getInstance('CommentRepository')
            ->updateComment($data);

        $this->redirect('/admin/comments/index');
    }
    
}
