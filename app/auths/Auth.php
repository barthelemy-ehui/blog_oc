<?php
namespace App\Auths;

use App\Exceptions\UserNotFoundException;
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
    
    public const UserAuthentifiedKeySession = 'user';
    
    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }
    
    public function register($data): bool
    {
        if(!$this->userRepository->verifyUserEmail($data['email'])) {
            $user = $this->userRepository->insertNewUser($data);
            $this->setSession($user);
            return true;
        }
        return false;
    }
    
    private function setSession($user): void
    {
        $this->session->set(self::UserAuthentifiedKeySession, $user);
    }
    
    public function check($email, $password)
    {
        try {
            if ($user = $this->userRepository->verifyUserCredential($email, $password)) {
                $this->setSession($user);
                return $user;
            }
        } catch (UserNotFoundException $e) {
            die('L\'utilisateur introuvable');
        }
        return null;
    }
    
    public function login($email, $password): bool
    {
        if($user = $this->check($email, $password)){
            return true;
        }
        return false;
    }
    
    public function logout(): void
    {
        $this->session->clear(self::UserAuthentifiedKeySession);
    }
}