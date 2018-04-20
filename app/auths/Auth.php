<?php
namespace App\Auths;

use App\Repositories\UserRepository;

class Auth
{
    /**
     * @var Session
     */
    private $session;
    
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    const USERAUTHENTIFIEDKEYSESSION = 'user';
    
    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }
    
    public function register($data)
    {
        if(!$this->userRepository->verifyUserEmail($data['email'])) {
            $user = $this->userRepository->insertNewUser($data);
            $this->setSession($user);
            return true;
        }
        return false;
    }
    
    private function setSession($user){
        $this->session->set(self::USERAUTHENTIFIEDKEYSESSION, $user);
    }
    
    public function check($email, $password)
    {
        if($user = $this->userRepository->verifyUserCredential($email, $password)) {
            $this->setSession($user);
            return $user;
        }
        return null;
    }
    
    public function login($email, $password){
        if($user = $this->check($email, $password)){
            return true;
        }
        return false;
    }
    
    public function logout(){
        $this->session->clear(self::USERAUTHENTIFIEDKEYSESSION);
    }
    
    public function IsRole($role)
    {
        
    }
}
