<?php

namespace App\repositories;


interface IRepository
{
    public function getAll();
    public function getById($id);
}