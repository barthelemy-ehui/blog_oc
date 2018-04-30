<?php
namespace App\controllers;


class AdminController extends Controller
{
    public function index() {
        echo $this->app->load('twig')->render('admin/index.twig');
    }
}