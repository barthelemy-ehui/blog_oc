<?php
namespace App\Routes;

use App\App;
use App\Auths\Auth;
use App\exceptions\BadUrlException;
use App\exceptions\NotLoginException;
use App\exceptions\UnrecognizeHttpMethodException;
use App\exceptions\UnrecognizeMethodException;

class Route
{
    const GET_METHOD  = 'get';
    const POST_METHOD  = 'post';
    const DELETE_METHOD  = 'delete';
    const PUT_METHOD  = 'put';
    const METHODS = [
        self::GET_METHOD,
        self::POST_METHOD,
        self::DELETE_METHOD,
        self::PUT_METHOD
    ];
    const HTTP_METHOD = 'http_method';
    
    private $stacksOfUrls = [];

    private $currentUri;
    
    private $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    
    public function get($uri, $controller)
    {
        
        if ($this->checkPathInfo($uri, static::GET_METHOD)) {
            $urlData = $this->fetchUrlData($uri);
            $this->callController($uri,$controller,$urlData);
        }
        $this->currentUri = $uri;
        return $this;
    }
    
    public function post($uri, $controller)
    {
        if ($this->checkPathInfo($uri, static::POST_METHOD)) {
            $this->callController($uri, $controller);
        }
        $this->currentUri = $uri;
        return $this;
    }
    
    public function all($uri, $controllerName){
        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        $controllerReflection = new \ReflectionClass($controller);
        
        
        $methods = $controllerReflection->getMethods();
        $methodName = '';
        $methods = array_map(function($obj){
            return strtolower($obj->name);
        },$methods);
        
        if(!in_array($methodName = $this->getUserClientMethod($uri),$methods)){
            //todo: remove it later on
            //throw new UnrecognizeMethodException();
            return $this;
        }
        
        $methodValue = $this->fetchUrlDataAll($uri);
        if( $controllerReflection->hasMethod($methodName) &&
            $controllerReflection->getMethod($methodName)->isPublic() &&
            $this->checkForHttpMethod($controllerReflection->getMethod($methodName)->getDocComment())
        ){
            $this->checkForAuthentification($controllerName, $methodName);
            if(!empty($methodValue)){
                (new $controller($this->app))->$methodName($methodValue);
            } else {
                (new $controller($this->app))->$methodName();
            }
            $this->currentUri = $uri;
        }
        return $this;
    }
    
    private function checkPathInfo($routeUrl, $http_method)
    {
        if(isset($_SERVER['REQUEST_URI'])) {
            $clientUrl = $_SERVER['REQUEST_URI'];
            $routeUrlReplaced = preg_replace('/\/?\{[[:alnum:]]+\}\/?/','',$routeUrl);
            return strpos($clientUrl,$routeUrlReplaced) !== false
                &&
                strtolower($http_method) === strtolower($_SERVER['REQUEST_METHOD']);
        }
        return false;
    }
    
    private  function callController($uri, $controllerPath, $methodValue = [])
    {
        list($controllerName,$methodName) = explode('::',$controllerPath);
        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        $this->checkForAuthentification($controllerName, $methodName);
        if(!empty($methodValue)){
            (new $controller($this->app))->$methodName($methodValue);
        } else {
            (new $controller($this->app))->$methodName();
        }
    }
    
    private function fetchUrlData($uri)
    {
        $pattern = '/{([a-z]+)}/';
        preg_match_all($pattern, $uri, $matches);
        

        $routeUrlExplode = explode('/', $uri);
        $clientUrlExplode = explode('/', $_SERVER['REQUEST_URI']);
        $urlUserData = array_values(array_diff($clientUrlExplode,$routeUrlExplode));
        
        $uriNameWithValue = [];
        
        if(count($urlUserData)>0){
            foreach($matches[1] as $key => $uriName) {
                $uriNameWithValue[$uriName] = $urlUserData[$key];
            }
        }
        return $uriNameWithValue;
    }
    
    public function setName($routeName)
    {
        $this->stacksOfUrls[$routeName] = $this->currentUri;
    }
    
    public function generateRouteUrl($routeName, $values = [], $method = '') {
        $uri = $this->stacksOfUrls[$routeName];
        if(empty($method)){
            $urlResult = preg_replace_callback('/\{(\w+)\}/', function($matches)use($values){
                return $values[$matches[1]];
            },$uri);
        } else {
            $valuesImplode = implode('/',$values);
            $routeSeparator = '/';
            $urlResult = $uri . $method . $routeSeparator . $valuesImplode;
        }
        
        return $urlResult;
    }
    
    private function getUserClientMethod($uri)
    {
        $userClientUrl = $_SERVER['REQUEST_URI'];
        $patternIndex = 0;
        if(strpos($userClientUrl, $uri) !== $patternIndex){
            // todo: remove it later on
            //throw new BadUrlException();
            return false;
        }
        
        $urlClient = $userClientUrl;
        if($uri !== '/'){
            $urlClient = substr_replace($userClientUrl,'',0,strlen($uri));
        }
       
        // By convention, after stripping out the route uri part, the first index among the separator is the name of the method
        // for instance, /home/show/123, we remove '/home' then keep '/show/123' and use show as the method of the controller
        $methodName = explode('/',$urlClient)[1];
        return strtolower($methodName);
    }
    
    private function checkForHttpMethod($getDocComments)
    {
        $commentsWildcardStripOut = trim(preg_replace('/(\*|\/)+/','', $getDocComments));
        
        if(empty($commentsWildcardStripOut)){
            throw new UnrecognizeHttpMethodException();
        }
    
        $httpMethodAsked = strtolower(explode('=',$commentsWildcardStripOut)[1]);
        if(!in_array($httpMethodAsked , static::METHODS)){
            throw new UnrecognizeHttpMethodException();
        }
        
        $userClientHttpMethod = strtolower($_SERVER['REQUEST_METHOD']);
        return $httpMethodAsked === $userClientHttpMethod;
    }
    
    private function fetchUrlDataAll($uri)
    {
        $userClientUrl = $_SERVER['REQUEST_URI'];

        $defaultRouteSeparator = '/';
        $urlClient = $userClientUrl;
        if($uri !== $defaultRouteSeparator){
            $urlClient = substr_replace($userClientUrl,'',0, strlen($uri));
        }
        
        $urlValues = explode('/',$urlClient);
        
        // because there is an empty value after explode, we don't need it, so we get rid of it
        array_shift($urlValues);
        
        // we get rid of the method name
        array_shift($urlValues);
        
        return $urlValues;
    }
    
    private function checkForAuthentification($controllerName, $methodName) {

        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        $controllerReflection = new \ReflectionClass($controller);
    
        $commentFromMethod = $controllerReflection->getMethod($methodName)->getDocComment();
        $admin = '/(auth=admin)/';
        preg_match($admin, $commentFromMethod,$matches);
        if($matches && !$this->app->load('session')->has(Auth::UserAuthentifiedKeySession)) {
           throw new NotLoginException();
        }
    }
}
