<?php
namespace App\models;


class User extends Model
{
    public $name;
    public $email;
    public $password;
    
    public function nameMaj(){
        return strtoupper($this->name);
    }
}