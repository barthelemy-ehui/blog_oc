<?php
require_once "../vendor/autoload.php";

use App\Routes\RouteSingleton;
use App\Repositories\RepositoryManager;
use App\Repositories\UserRepository;
use App\Routes\Route;
use App\App;

$loader = new Twig_Loader_Filesystem('../app/views');
$twig = new Twig_Environment($loader, array(
    'cache' => '../cache',
));


$repoManager = new RepositoryManager();
$repoManager->add([
    'UserRepository' => new UserRepository()
]);

$app = new App();
$app->add([
    'twig'=>$twig,
    'RepoManager'=>$repoManager
]);


/** @var Route $route */
$route = RouteSingleton::getInstance($app);

// declaration des routes des routes
$route->get('/hello/{name}','HomeController::inscription')->setName('auth.hello');
$route->all('/', 'testController')->setName('controller.alone');