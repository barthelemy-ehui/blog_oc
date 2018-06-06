<?php
namespace App\Controllers;

use App\Auths\Auth;
use App\Exceptions\NaNException;
use App\Models\Post;
use App\Models\User;
use App\Validation\Validator;

class PostController extends Controller
{
    /**
     * http_method=get
     * auth=admin
     */
    public function index(){
        
        $posts = $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->getAll();

        echo $this->app->load('twig')->render('admin/post/index.twig', [
            'posts' => $posts
        ]);
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function add(){
        
        $errors = [];
        $session = $this->app->load('session');
        if($session->has(Validator::class)) {
            $errors = $session->get(Validator::class);
            $session->clear(Validator::class);
        }
        
        echo $this->app->load('twig')->render('admin/post/add.twig',[
            'Post' => Post::class,
            'errors' => $errors,
        ]);
    }
    
    /**
     * http_method=post
     * auth=admin
     */
    public function store(){
        
        $validator = new Validator();
        $validator->addRule([
            'slug' => Validator::REQUIRED,
            'title' => Validator::REQUIRED,
            'description' => Validator::REQUIRED,
            'content' => Validator::REQUIRED,
            'status' => Validator::REQUIRED,
            'publish_at' => Validator::REQUIRED,
        ]);
        
        $data = $validator->validate();
        $errors = $validator->getErrors();
        if(count($errors['errors'])>0){
            $this->app->load('session')->set(Validator::class, $errors);
            $this->redirect('/admin/posts/add');
            return;
        }
        
        /** @var User $user */
        $user = $this->app->load('session')->get(Auth::UserAuthentifiedKeySession);
        
        $data = array_merge($data, [
            'author_id' => $user->getId()
        ]);
        
        $data['slug'] =  str_replace(' ','-', $data['slug']);
        
        $this->app->load('repoManager')->getInstance('PostRepository')
            ->insertNewPost($data);
        
        $this->redirect('/admin/posts/index');
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function edit($id){
    
        if(!(int) $id[0]){
            throw new NaNException();
        }

        $errors = [];
        $session = $this->app->load('session');
        if($session->has(Validator::class)) {
            $errors = $session->get(Validator::class);
            $session->clear(Validator::class);
        }
        
        $post = $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->getById($id[0]);
        
        echo $this->app->load('twig')->render('admin/post/edit.twig', [
            'post' => $post,
            'Post' => Post::class,
            'errors' => $errors
        ]);
    }
    
    /**
     * http_method=post
     * auth=admin
     */
    public function update() {
        
        $validator = new Validator();
        $validator->addRule([
            'slug' => Validator::REQUIRED,
            'title' => Validator::REQUIRED,
            'description' => Validator::REQUIRED,
            'content' => Validator::REQUIRED,
            'status' => Validator::REQUIRED,
            'publish_at' => Validator::REQUIRED,
            'id' => Validator::REQUIRED
        ]);
        
        $data = $validator->validate();
        $errors = $validator->getErrors();
        if( count($errors['errors'])>0 ) {
            $this->app->load('session')->set(Validator::class, $errors);
            $this->redirect('/admin/posts/edit/' . $data['id']);
            return;
        }
        
        $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->updatePost($data);
        
        $this->redirect('/admin/posts/index');
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function delete($id){
    
        if(!(int) $id[0]){
            throw new NaNException();
        }
    
        $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->deletePostById($id[0]);
        
        $this->redirect('/admin/posts/index');
    }
    
    /**
     * http_method=get
     */
    public function getPostBySlug($slug) {
    
        $errors = [];
        $session = $this->app->load('session');
        if($session->has(Validator::class)) {
            $errors = $session->get(Validator::class);
            $session->clear(Validator::class);
        }
        
        $post =  $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->getById($slug);
        
        echo $this->app->load('twig')->render('',[
            'post' => $post,
            'errors' => $errors
        ]);
    }
    
    public function getPostById($id) {
        $post =  $this->app->load('repoManager')
            ->getInstance('PostRepository')
            ->getById($id);
        
    }
}
