<?php

namespace App\repositories;


abstract class Repository
{
    protected $pdo;
    
    /**
     * Repository constructor.
     * @param \Pdo $pdo
     */
    public function __construct(\Pdo $pdo)
    {
    
        $this->pdo = $pdo;
    }
}