<?php
require_once "../vendor/autoload.php";

use App\Routes\RouteSingleton;
use App\Database\DBsingleton;
use App\Repositories\RepositoryManager;
use App\Repositories\UserRepository;
use App\Routes\Route;
use App\App;

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
    'repoManager'=>$repoManager
]);


/** @var Route $route */
$route = RouteSingleton::getInstance($app);
$twig->addGlobal('route',$route);

// declaration des routes des routes


$route->get('/hello/{name}','HomeController::inscription')->setName('auth.hello');
$route->all('/test', 'TestController')->setName('controller.alone');