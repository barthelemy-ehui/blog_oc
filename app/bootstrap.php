<?php
session_start();

require_once "../vendor/autoload.php";

use App\App;
use App\Auths\Session;
use App\Auths\Auth;
use App\Database\DBsingleton;
use App\Repositories\RepositoryManager;
use App\Repositories\UserRepository;
use App\Routes\Route;
use App\Routes\RouteSingleton;

$loader = new Twig_Loader_Filesystem('../app/views');
$twig = new Twig_Environment($loader, array(
    //'cache' => '../cache',
));

$pdo = DBSingleton::getInstance();
$session = new Session();
$userRepository = new UserRepository($pdo);
$repoManager = new RepositoryManager();
$repoManager->add([
    'UserRepository' => $userRepository
]);

$app = new App();

$app->add([
    'twig'=>$twig,
    'repoManager'=>$repoManager,
    'session' => $session,
    'auth' => new Auth($session, $userRepository),
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
//$route->all('/home', 'HomeController');
//$route->all('/test', 'TestController');

/**
 * Admin route
 */
$route->get('/admin', 'AdminController::index');
$route->all('/admin/register', 'RegisterController');
/**
 * Authentification
 */