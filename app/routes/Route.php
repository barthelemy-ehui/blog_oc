<?php
namespace App\Routes;

use App\exceptions\BadUrlException;
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
    
    public function get($uri, $controller)
    {
        if ($this->checkPathInfo($uri, static::GET_METHOD)) {
            $urlData = $this->fetchUrlData($uri);
            $this->callController($controller,$urlData);
        }
    }
    
    public function post($uri, $controller)
    {
        if ($this->checkPathInfo($uri, static::POST_METHOD)) {
            $this->callController($controller);
        }
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
    
    private  function callController($controllerPath, $methodValue = [])
    {
        list($controllerName,$method) = explode('::',$controllerPath);
        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        if(!empty($methodValue)){
            (new $controller)->$method($methodValue);
        } else {
            (new $controller)->$method();
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
            throw new UnrecognizeMethodException();
        }
        
        $methodValue = $this->fetchUrlDataAll($uri);

        if( $controllerReflection->hasMethod($methodName) &&
            $controllerReflection->getMethod($methodName)->isPublic() &&
            $this->checkForHttpMethod($controllerReflection->getMethod($methodName)->getDocComment())
        ){
            
            if(!empty($methodValue)){
                (new $controller)->$methodName($methodValue);
            } else {
                (new $controller)->$methodName();
            }
        }

    }
    
    private function getUserClientMethod($uri)
    {
        $userClientUrl = $_SERVER['REQUEST_URI'];
        
        $patternIndex = 0;
        if(strpos($userClientUrl, $uri) !== $patternIndex){
            throw new BadUrlException();
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
            $urlClient = substr_replace($userClientUrl,'',0,strlen($uri));
        }
        
        $urlValues = explode('/',$urlClient);
        
        // because there is an empty value after explode, we don't need it, so we get rid of it
        array_shift($urlValues);
        
        // we get rid of the method name
        array_shift($urlValues);
        
        return $urlValues;
    }
    
    
}
