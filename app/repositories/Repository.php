<?php

namespace App\repositories;


abstract class Repository
{
    abstract public function getAll();
    abstract public function getById($id);
}