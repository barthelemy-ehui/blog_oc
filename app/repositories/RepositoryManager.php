<?php
namespace App\Repositories;

class RepositoryManager
{
    private $instances = [];
    
    public function add($instances): void
    {
        $this->instances = $instances;
    }
    
    public function getInstance($instanceName) 
    {
        return $this->instances[$instanceName];
    }
}