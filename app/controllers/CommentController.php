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
           'email' => Validator::EMAIL,
           'post_id' => Validator::REQUIRED,
           'slug' => Validator::REQUIRED,
        ]);
    
        $data = $validator->validate();
        $errors = $validator->getErrors();
        
        if(count($errors['errors'])>0) {
            $this->app->load('session')
                 ->set(Validator::class, $errors);
            
            $this->redirect('/post/' . $errors['datas']['slug']);
            return;
        }

        // only need slug for the redirection
        $slug = $data['slug'];
        unset($data['slug']);
        
        $cmt = $this->app->load('repoManager')
            ->getInstance('CommentRepository')
            ->insertNewComment($data);
    
    
        $name = "Unknown";
        $email = $cmt->getEmail();
        $recipient = "b.ehuinda@gmail.com";
        $mail_body = $cmt->getContent();
        $subject = $cmt->getTitle();
        $header = "From: ". $name . " <" . $email . ">\r\n";
    
        mail($recipient, $subject, $mail_body, $header);
        
        $this->redirect('/post/' . $slug);
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