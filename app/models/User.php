<?php
namespace App\Models;

class User extends Model
{
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $created_at;
    
    public function nameMaj(){
        return strtoupper($this->name);
    }
}
