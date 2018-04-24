<?php

namespace App\Repositories;

use App\Models\User;
use \Pdo;

class UserRepository extends Repository implements IRepository
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
        $sqlStmt = 'SELECT * FROM users WHERE email = :email AND password = :password';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password
        ]);
        return $stmt->fetchObject(User::class);
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
            'created_at' => (new \DateTime('now'))->format('Y-m-d H:i:s')
        ]);
        $sqlStmt = 'INSERT into users (firstname,lastname,email,password,created_at) VALUES (:firstname,:lastname,:email,:password, :created_at)';
        $stmt = $this->pdo->prepare($sqlStmt);
        $stmt->execute($data);
    
        return $this->getById($this->pdo->lastInsertId());
    }
}