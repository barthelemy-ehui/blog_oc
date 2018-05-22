<?php
namespace App\Routes;

use App\App;
use App\Auths\Auth;
use App\exceptions\NotLoginException;
use App\exceptions\RequestUriException;
use App\exceptions\UnrecognizeHttpMethodException;

class Route
{
    public const GET_METHOD  = 'get';
    public const POST_METHOD  = 'post';
    public const DELETE_METHOD  = 'delete';
    public const PUT_METHOD  = 'put';
    public const METHODS = [
        self::GET_METHOD,
        self::POST_METHOD,
        self::DELETE_METHOD,
        self::PUT_METHOD
    ];
    public const HTTP_METHOD = 'http_method';
    
    private $stacksOfUrls = [];

    private $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    
    public function get($uri, $controller): void
    {
        $this->stacksOfUrls[$uri] = [
            'type' => 'getUrl',
            'controller' => $controller
        ];
    }
    
    private function getUrl($uri, $controller): void
    {
        if ($this->checkPathInfo($uri, static::GET_METHOD)) {
            $urlData = $this->fetchUrlData($uri);
            $this->callController($controller, $urlData);
            return;
        }
        $this->returnErrorPage();
    }
    
    public function post($uri, $controller): void
    {
        $this->stacksOfUrls[$uri] = [
            'type' => 'postUrl',
            'controller' => $controller
        ];
    }
    
    private function postUrl($uri, $controller): void
    {
        if ($this->checkPathInfo($uri, static::POST_METHOD)) {
            $this->callController($controller);
            return;
        }
        
        $this->returnErrorPage();
    }

    public function all($uri, $controllerName): void
    {
        $this->stacksOfUrls[$uri] = [
            'type' => 'allUrl',
            'controller' => $controllerName
        ];
    }

    private function allUrl($uri, $controllerName)
    {
        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        $controllerReflection = new \ReflectionClass($controller);
        
        
        $methods = $controllerReflection->getMethods();
        $methodName = '';
        $methods = array_map(
            function ($obj) {
                return strtolower($obj->name);
            }, $methods
        );
        
        if(!in_array($methodName = $this->getUserClientMethod($uri), $methods)) {
            return $this;
        }
        
        $methodValue = $this->fetchUrlDataAll($uri);
        if($controllerReflection->hasMethod($methodName) 
            && $controllerReflection->getMethod($methodName)->isPublic() 
            && $this->checkForHttpMethod($controllerReflection->getMethod($methodName)->getDocComment())
        ) {
            $this->checkForAuthentification($controllerName, $methodName);
            if(!empty($methodValue)) {
                (new $controller($this->app))->$methodName($methodValue);
            } else {
                (new $controller($this->app))->$methodName();
            }
            return;
        }
        $this->returnErrorPage();
    }
    
    private function checkPathInfo($routeUrl, $http_method): bool
    {
        if(isset($_SERVER['REQUEST_URI'])) {
            $clientUrl = $_SERVER['REQUEST_URI'];
            $isHttpMethod = strtolower($http_method) === strtolower($_SERVER['REQUEST_METHOD']);

            if($clientUrl == $routeUrl && $isHttpMethod) {
                return true;
            }
            
            $routeUrlExplode = ['/'];
            if($routeUrl !== '/') {
                $routeUrlresult = preg_replace('/\/?\{(\w+)\}+\/?/', '', $routeUrl);
                $routeUrlExplode = array_filter(
                    explode('/', $routeUrlresult), function ($v) {
                        return $v !== '';
                    }
                );
            }
            
            $clientUrlExplode = array_filter(
                explode('/', $clientUrl), function ($v) {
                    return $v !== ''; 
                }
            );
            $clientUrlSpliced = array_splice($clientUrlExplode, 0, count($routeUrlExplode));
            
            if(implode('/', $routeUrlExplode) === implode('/', $clientUrlSpliced) && $isHttpMethod) {
                return true;
            }
        }
        return false;
    }
    
    private  function callController($controllerPath, $methodValue = []): void
    {
        [$controllerName,$methodName] = explode('::', $controllerPath);
        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        $this->checkForAuthentification($controllerName, $methodName);
        if(!empty($methodValue)) {
            (new $controller($this->app))->$methodName($methodValue);
        } else {
            (new $controller($this->app))->$methodName();
        }
    }
    
    private function fetchUrlData($uri): array
    {
        $pattern = '/{([a-z]+)}/';
        preg_match_all($pattern, $uri, $matches);
        
        $routeUrlExplode = explode('/', $uri);
        $clientUrlExplode = explode('/', $_SERVER['REQUEST_URI']);
        $urlUserData = array_values(array_diff($clientUrlExplode, $routeUrlExplode));
        
        $uriNameWithValue = [];
        
        if(count($urlUserData)>0) {
            foreach($matches[1] as $key => $uriName) {
                $uriNameWithValue[$uriName] = $urlUserData[$key];
            }
        }
        return $uriNameWithValue;
    }
    
    public function generateRouteUrl($routeName, $values = [], $method = '') 
    {
        $uri = $this->stacksOfUrls[$routeName];
        if(empty($method)) {
            $urlResult = preg_replace_callback(
                '/\{(\w+)\}/', function ($matches) use ($values) {
                    return $values[$matches[1]];
                }, $uri
            );
        } else {
            $valuesImplode = implode('/', $values);
            $routeSeparator = '/';
            $urlResult = $uri . $method . $routeSeparator . $valuesImplode;
        }
        
        return $urlResult;
    }
    
    private function getUserClientMethod($uri)
    {
        $userClientUrl = $_SERVER['REQUEST_URI'];
        $patternIndex = 0;
        if(strpos($userClientUrl, $uri) !== $patternIndex) {
            return false;
        }
        
        $urlClient = $userClientUrl;
        if($uri !== '/') {
            $urlClient = substr_replace($userClientUrl, '', 0, strlen($uri));
        }
       
        $methodName = explode('/', $urlClient)[1];
        return strtolower($methodName);
    }
    
    private function checkForHttpMethod($getDocComments): bool
    {
        $commentsWildcardStripOut = trim(preg_replace('/(\*|\/)+/', '', $getDocComments));
        
        if(empty($commentsWildcardStripOut)) {
            throw new UnrecognizeHttpMethodException();
        }
    
        $httpMethod = '/(http_method=(post|get|delete|put))/';
        preg_match($httpMethod, $commentsWildcardStripOut, $matches);
        $httpMethodAsked = $matches[2];
        if(!in_array($httpMethodAsked, static::METHODS)) {
            throw new UnrecognizeHttpMethodException();
        }
        
        $userClientHttpMethod = strtolower($_SERVER['REQUEST_METHOD']);
        return $httpMethodAsked === $userClientHttpMethod;
    }
    
    private function fetchUrlDataAll($uri): array
    {
        $userClientUrl = $_SERVER['REQUEST_URI'];

        $defaultRouteSeparator = '/';
        $urlClient = $userClientUrl;
        if($uri !== $defaultRouteSeparator) {
            $urlClient = substr_replace($userClientUrl, '', 0, strlen($uri));
        }
        
        $urlValues = explode('/', $urlClient);
        
        array_shift($urlValues);
        
        array_shift($urlValues);
        
        return $urlValues;
    }
    
    private function checkForAuthentification($controllerName, $methodName): void
    {

        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        $controllerReflection = new \ReflectionClass($controller);
    
        $commentFromMethod = $controllerReflection->getMethod($methodName)->getDocComment();
        $admin = '/(auth=admin)/';
        preg_match($admin, $commentFromMethod, $matches);
        if($matches && !$this->app->load('session')->has(Auth::UserAuthentifiedKeySession)) {
            throw new NotLoginException();
        }
    }
    
    public function run(): void
    {
        
        if(!isset($_SERVER['REQUEST_URI'])) {
            throw new RequestUriException();
        }

        $requestUri = $_SERVER['REQUEST_URI'];
        $uriExplode = array_filter(
            explode('/', $requestUri), function ($v) {
                return $v !== '';
            }
        );
        
        $urlKeysArchive = array_keys($this->stacksOfUrls);
        
        $urlKeysWithValueKey = [];
        $urlKeys = array_map(
            function ($key) use (&$urlKeysWithValueKey) {
                $toReturnLater =  preg_replace('/\/?\{(\w+)\}+\/?/', '', $key);
                $urlKeysWithValueKey[$toReturnLater] = $key;
                return $toReturnLater;
            }, $urlKeysArchive
        );
        
        $urlFind = $this->recursive($uriExplode, $urlKeys);
        
        
        
        $clientUri = $urlKeysWithValueKey[$urlFind];
        $st = $this->stacksOfUrls[$clientUri];
        $this->{$st['type']}($clientUri, $st['controller']);
    }
    
    private function recursive($arr, $keys)
    {
        
        $urlToCheck = '/' . implode('/', $arr);
        if(array_key_exists($urlToCheck, array_flip($keys))) {
            return $urlToCheck;
        }
        
        if(count($arr)==0) {
            return false;
        }
        array_pop($arr);
        return $this->recursive($arr, $keys);
    }
    
    private function returnErrorPage(): void
    {
        die('La page que vous cherchez n\'existe pas ou a été supprimée. Veuillez contacter l\'administrateur du site');
    }
}
