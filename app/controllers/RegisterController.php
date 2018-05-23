<?php
namespace App\controllers;

use App\Auths\Auth;
use App\Exceptions\NaNException;
use App\Models\User;
use App\Validation\Validator;

class RegisterController extends Controller
{
    
    /**
     * http_method=get
     * auth=admin
     */
    public function index(): void
    {
        echo $this->app->load('twig')->render('admin/auth/register.twig');
    }
    
    /**
     * http_method=get
     */
    public function connect(): void
    {
        $errors = [];
        if($this->app->load('session')->has(Auth::UserNotFound)){
            $errors[Auth::UserNotFound] = $this->app->load('session')->get(Auth::UserNotFound);
            $this->app->load('session')->clear(Auth::UserNotFound);
        }

        echo $this->app->load('twig')->render('admin/auth/connect.twig', [
            'errors' => $errors
        ]);
    }
    
    /**
     * http_method=post
     */
    public function store(): void
    {

        $validator = new Validator();
        $validator->addPasswordToCompare('passwordConfirm');
        $validator->addRule(
            [
            'firstname' => Validator::REQUIRED,
            'lastname' => Validator::REQUIRED,
            'email' => Validator::REQUIRED_EMAIL,
            'password' => Validator::REQUIRED_PASSWORD_COMPARE
            ]
        );
        
        $data = $validator->validate();
        $errors = $validator->getErrors();
        if($errors['errors'] && $errors['datas']) {
            echo $this->app->load('twig')->render(
                'admin/auth/register.twig', [
                'errors' => $errors['errors'],
                'datas' => $errors['datas']
                ]
            );
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
     * http_method=get
     * auth=admin
     */
    public function edit($id): void
    {
        
        if(!(int) $id[0]) {
            throw new NaNException();
        }
        
        $errors = [];
        $session = $this->app->load('session');
        if($session->has(Validator::class)) {
            $errors = $session->get(Validator::class);
            $session->clear(Validator::class);
        }
        
        $user = $this->app->load('repoManager')
            ->getInstance('UserRepository')
            ->getById($id[0]);
        
        
        echo $this->app->load('twig')->render(
            'admin/user/edit.twig', [
            'user' => $user,
            'User' => User::class,
            'errors' => $errors
            ]
        );
    }
    
    /**
     * http_method=post
     * auth=admin
     */
    public function update(): void
    {
        
        $validator = new Validator();
        $validator->addPasswordToCompare('passwordConfirm');
        $validator->addRule(
            [
            'firstname' => Validator::REQUIRED,
            'lastname' => Validator::REQUIRED,
            'email' => Validator::REQUIRED_EMAIL,
            'password' => Validator::REQUIRED_PASSWORD_COMPARE,
            'id' => Validator::REQUIRED
            ]
        );
    
    
        $data = $validator->validate();
        $errors = $validator->getErrors();
        
        if($errors['errors'] && $errors['datas']) {
            $this->app->load('session')->set(Validator::class, $errors);
            $this->redirect('/admin/register/edit/' . $errors['datas']['id']);
            return;
        }
    
        
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
    
        $this->app->load('repoManager')
            ->getInstance('UserRepository')
            ->updateUser($data);
        
        $this->redirect('/admin/users');
    }
    
    /**
     * http_method=get
     * auth=admin
     */
    public function deleteUserById($id): void
    {
        
        if(!(int) $id[0]) {
            throw new NaNException();
        }
    
        $this->app->load('repoManager')
            ->getInstance('UserRepository')
            ->deleteUserById($id[0]);
        $this->redirect('/admin/users');
    }
    
    /**
     * http_method=post
     */
    public function login(): void
    {
        $validator = new Validator();
        $validator->addPasswordToCompare('passwordConfirm');
        $validator->addRule(
            [
            'email' => Validator::REQUIRED_EMAIL,
            'password' => Validator::REQUIRED
            ]
        );
    
        $datas = $validator->validate();
        $errors = $validator->getErrors();
        

        if(count($errors['errors'])) {
            echo $this->app->load('twig')->render(
                'admin/auth/connect.twig', [
                'datas' => $errors['datas'],
                'errors' => $errors['errors']
                ]
            );
            return;
        }
        
        if($this->app->load('auth')->login($datas['email'], $datas['password'])) {
            $this->redirect('/admin');
        } else {
            $this->app->load('session')->set(Auth::UserNotFound,'Email ou mot de passe incorrect');
            $this->redirect('/admin/register/connect');
        }

    }
    
    /**
     * http_method=get
     */
    public function logout(): void
    {
        if($this->app->load('session')->has(Auth::UserAuthentifiedKeySession)) {
            $this->app->load('session')->clear(Auth::UserAuthentifiedKeySession);
        }
        
        header('Location: /admin/register/connect');
    }
}