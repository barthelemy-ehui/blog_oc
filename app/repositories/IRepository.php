<?php
namespace App\Repositories;


interface IRepository
{
    public function getAll();
    public function getById($id);
}