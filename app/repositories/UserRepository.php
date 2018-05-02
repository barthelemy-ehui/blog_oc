<?php

namespace App\Repositories;

use App\Models\User;
use \Pdo;
use App\Exceptions\UserNotFoundException;

class UserRepository extends Repository implements IRepositoryInterface
{
    
    public function getAll()
    {
        $sqlStmt = 'SELECT * FROM users';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_CLASS,User::class);
    }
    
    public function verifyUserCredential($email, $password)
    {
        $sqlStmt = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
            ':email' => $email
        ]);
        $user = $stmt->fetchObject(User::class);
        if(!$user){
            throw new UserNotFoundException();
        }
        
        if(password_verify($password,$user->getPassword())) {
            return $user;
        }
        return false;
    }

    public function getById($id)
    {
        $sqlStmt = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
            ':id' => $id
        ]);
        
        return $stmt->fetchObject(User::class);
    }
    
    public function verifyUserEmail($email)
    {
        $sqlStmt = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
            ':email' => $email
        ]);
        return $stmt->fetchObject(User::class);
    }
    
    public function insertNewUser($data)
    {
        $data = array_merge($data, [
            'create_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
        ]);
        
        $sqlStmt = 'INSERT into users (firstname,lastname,email,password,create_at) VALUES (:firstname,:lastname,:email,:password, :create_at)';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute($data);
        
        return $this->getById($this->pdo->lastInsertId());
    }
}
