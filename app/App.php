<?php

namespace App;


class App
{
    private $instances;
    
    public function add($instances){
        $this->instances = $instances;
    }
    
    public function load($objName){
        return $this->instances[$objName];
    }
}