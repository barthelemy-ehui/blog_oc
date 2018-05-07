<?php
namespace App\controllers;

use App\Auths\Auth;
use App\Validation\Validator;

class RegisterController extends Controller
{
    
    /**
     * http_method=get
     * auth=admin
     */
    public function index() {
        echo $this->app->load('twig')->render('admin/auth/register.twig');
    }
    
    /**
     * http_method=get
     */
    public function connect() {
        echo $this->app->load('twig')->render('admin/auth/connect.twig');
    }
    
    /**
     * http_method=post
     */
    public function store() {

        $validator = new Validator();
        $validator->addPasswordToCompare('passwordConfirm');
        $validator->addRule([
            'firstname' => Validator::REQUIRED,
            'lastname' => Validator::REQUIRED,
            'email' => Validator::REQUIRED_EMAIL,
            'password' => Validator::REQUIRED_PASSWORD_COMPARE
        ]);
        
        $data = $validator->validate();
        $errors = $validator->getErrors();
        if($errors['errors'] && $errors['datas']){
           echo $this->app->load('twig')->render('admin/auth/register.twig',[
               'errors' => $errors['errors'],
               'datas' => $errors['datas']
           ]);
           return;
        }
    
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $user = $this->app
            ->load('repoManager')
            ->getInstance('UserRepository')
            ->insertNewUser($data);
        
        header('Location: /admin/users');
    }
    
    /**
     * http_method=post
     */
    public function login()
    {
        $validator = new Validator();
        $validator->addPasswordToCompare('passwordConfirm');
        $validator->addRule([

            'email' => Validator::REQUIRED_EMAIL,
            'password' => Validator::REQUIRED
        ]);
    
        $datas = $validator->validate();
        $errors = $validator->getErrors();
        

        if(count($errors['errors'])){
            echo $this->app->load('twig')->render('admin/auth/connect.twig',[
                'datas' => $errors['datas'],
                'errors' => $errors['errors']
            ]);
            return;
        }
        
        $stmt = $this->app->load('auth')->login($datas['email'], $datas['password']);
        if($this->app->load('auth')->login($datas['email'], $datas['password'])){
            header("Location: /admin");
        }

    }
    
    /**
     * http_method=get
     */
    public function logout()
    {
        if($this->app->load('session')->has(Auth::UserAuthentifiedKeySession)){
            $this->app->load('session')->clear(Auth::UserAuthentifiedKeySession);
        }
        
        header('Location: /admin/register/connect');
    }
}