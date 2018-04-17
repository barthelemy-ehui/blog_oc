<?php

use App\Routes\Route;


//Route::get('/hello/{name}','HomeController::inscription');

Route::all('/test', 'TestController');
//Route::get('/inscription/{name}/{age}', 'TestController::show');
//inscription/index

//// /inscription/show
/// /inscription/nom
/// /inscription/prenom
/// /inscription/create
/// /inscription/update
/// /inscription/add

/// /show
/// /nom
/// /prenom
/// /create
/// /update
/// /add

/// D'abord je fais la fonctionnalité
/// Ensuite j'ajoute des contraintes
/// J'ajoute les exceptions
/// Retour des bons messages d'erreur

//Route::get('/hello/{name}', 'HomeController::index');

//Route::get('/show', 'HomeController::show');
//Route::post('/show', 'HomeController::show');
