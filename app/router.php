<?php

use App\Route;

Route::get('/hello/{name}', 'HomeController::index');

Route::get('/show', 'HomeController::show');
