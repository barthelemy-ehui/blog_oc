<?php

namespace App\Repositories;


class RepositoryManager
{
    private $instances = [];
    
    public function add($instances){
        $this->instances = $instances;
    }
    
    public function getInstance($instanceName) {
        return $this->instances[$instanceName];
    }
}
