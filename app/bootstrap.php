<?php
session_start();
error_reporting(0);

require_once __DIR__."/../vendor/autoload.php";

use App\App;
use App\Auths\Session;
use App\Auths\Auth;
use App\Database\DBsingleton;
use App\Repositories\RepositoryManager;
use App\Repositories\UserRepository;
use App\Routes\Route;
use App\Routes\RouteSingleton;
use App\Repositories\PostRepository;
use App\Repositories\CommentRepository;

$loader = new Twig_Loader_Filesystem( __DIR__. '/../app/views');
$twig = new Twig_Environment($loader, array(
));

$twig->addExtension(new Twig_Extensions_Extension_Text());

$pdo = DBSingleton::getInstance();
$session = new Session();
$userRepository = new UserRepository($pdo);
$postRepository = new PostRepository($pdo);
$commentRepository = new CommentRepository($pdo);

$repoManager = new RepositoryManager();
$repoManager->add(
    [
    'UserRepository' => $userRepository,
    'PostRepository' => $postRepository,
    'CommentRepository' => $commentRepository
    ]
);

$app = new App();

$app->add(
    [
    'twig'=>$twig,
    'repoManager'=>$repoManager,
    'session' => $session,
    'auth' => new Auth($session, $userRepository),
    ]
);

/**
 * @var Route $route
*/
$route = RouteSingleton::getInstance($app);
$twig->addGlobal('route', $route);

if($session->has(Auth::USERAUTHENTIFIEDKEYSESSION)){
    $twig->addGlobal('user',$session->get(Auth::USERAUTHENTIFIEDKEYSESSION));
}

/**
 * front-end route
 */
$route->get('/', 'HomeController::index');
$route->get('/blog/{offset}/{limit}', 'PostController::indexWithPagination');
$route->get('/post/{slug}', 'PostController::getPostBySlug');
$route->post('/comment/store', 'CommentController::store');
$route->post('/send/email', 'EmailController::send');

/**
 * Admin route
 */
$route->get('/admin', 'AdminController::index');
$route->get('/admin/users', 'AdminController::users');
$route->all('/admin/register', 'RegisterController');
$route->all('/admin/posts', 'PostController');
$route->all('/admin/comments', 'CommentController');


try {
    $route->run();
} catch (\App\exceptions\RequestUriException $e) {
    echo ('Message: ' . $e->getMessage());
} catch (\App\Exceptions\NaNException|DivisionByZeroError|ArgumentCountError|\App\Exceptions\BadUrlException $e) {
    echo $app->load('twig')->render('/error404.twig');
} catch(\App\Exceptions\NotLoginException $e){
    echo $app->load('twig')->render('admin/errorNotLogin.twig');
}
