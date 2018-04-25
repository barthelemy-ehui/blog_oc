<?php
session_start();

require_once "../vendor/autoload.php";

use App\App;
use App\Auths\Session;
use App\Database\DBsingleton;
use App\Repositories\RepositoryManager;
use App\Repositories\UserRepository;
use App\Routes\Route;
use App\Routes\RouteSingleton;

$loader = new Twig_Loader_Filesystem('../app/views');
$twig = new Twig_Environment($loader, array(
    'cache' => '../cache',
));

$pdo = DBSingleton::getInstance();

$repoManager = new RepositoryManager();
$repoManager->add([
    'UserRepository' => new UserRepository($pdo)
]);

$app = new App();
$app->add([
    'twig'=>$twig,
    'repoManager'=>$repoManager,
    'session' => new Session()
]);


/** @var Route $route */
$route = RouteSingleton::getInstance($app);
$twig->addGlobal('route',$route);

/**
 * Authentification
 */


/**
 * front-end route
 */
$route->get('/hello/{name}','HomeController::inscription')->setName('auth.hello');
$route->all('/test', 'TestController')->setName('controller.alone');

/**
 * Admin route
 */

/**
 * Authentification
 */
