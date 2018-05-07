<?php
namespace App\Models;

class User extends Model
{
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $password;
    protected $created_at;
    protected $update_at;
    
    public function nameMaj(){
        return strtoupper($this->firstname);
    }
    
    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    
    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    
}