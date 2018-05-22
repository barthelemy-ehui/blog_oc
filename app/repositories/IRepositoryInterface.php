<?php
namespace App\Repositories;


interface IRepositoryInterface
{
    public function getAll();
    public function getById($id);
}
