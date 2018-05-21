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
    
    const FIRSTNAME = 'firstname';
    const LASTNAME = 'lastname';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const PASSWORDCONFIRM = 'passwordConfirm';
    
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
    
    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }
    
    /**
     * @param mixed $create_at
     */
    public function setCreateAt($create_at): void
    {
        $this->create_at = $create_at;
    }
    
    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }
    
    /**
     * @param mixed $update_at
     */
    public function setUpdateAt($update_at): void
    {
        $this->update_at = $update_at;
    }
    
}
