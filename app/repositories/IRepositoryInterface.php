<?php

namespace App\repositories;


interface IRepositoryInterface
{
    public function getAll();
    public function getById($id);
}