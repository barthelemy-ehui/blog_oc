<?php
namespace App\Models;

class User extends Model
{
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $password;
    protected $create_at;
    protected $update_at;
    
    public const FIRSTNAME = 'firstname';
    public const LASTNAME = 'lastname';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const PASSWORDCONFIRM = 'passwordConfirm';
    
    public function nameMaj()
    {
        return strtoupper($this->firstname);
    }
    
    public function getFirstname()
    {
        return $this->firstname;
    }
    
    public function getLastname()
    {
        return $this->lastname;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getCreateAt()
    {
        return $this->create_at;
    }
    
    public function setCreateAt($create_at): void
    {
        $this->create_at = $create_at;
    }
    
    public function getUpdateAt()
    {
        return $this->update_at;
    }
    
    public function setUpdateAt($update_at): void
    {
        $this->update_at = $update_at;
    }
    
}