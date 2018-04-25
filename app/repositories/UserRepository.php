<?php

namespace App\Repositories;


class UserRepository extends Repository implements IRepository
{
    
    public function getAll()
    {
        //$this->pdo->query('SELECT * FROM users');
        $virtualUser1 = new stdClass();
        $virtualUser1->name = 'alex';
        $virtualUser1->email = 'alex@gmail.com';

        $virtualUser2 = new stdClass();
        $virtualUser2->name = 'bose';
        $virtualUser2->email = 'bose@gmail.com';
        
        return [$virtualUser1, $virtualUser2];
    }
    
    public function getById($id)
    {
        // TODO: Implement getById() method.
    }
}