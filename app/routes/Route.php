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
    
    public static function get($uri, $controller)
    {
        if (static::checkPathInfo($uri, static::GET_METHOD)) {
            $urlData = static::fetchUrlData($uri);
            static::callController($controller,$urlData);
        }
    }
    
    public static function post($uri, $controller)
    {
        if (static::checkPathInfo($uri, static::POST_METHOD)) {
            static::callController($controller);
        }
    }
    
    private static function checkPathInfo($routeUrl, $http_method)
    {
        if(isset($_SERVER['PATH_INFO'])) {
            $clientUrl = $_SERVER['PATH_INFO'];
            $routeUrlReplaced = preg_replace('/\/?\{[[:alnum:]]+\}\/?/','',$routeUrl);
            return strpos($clientUrl,$routeUrlReplaced) !== false
                &&
                strtolower($http_method) === strtolower($_SERVER['REQUEST_METHOD']);
        }
        return false;
    }
    
    private static function callController($controllerPath, $methodValue = [])
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
    
    private static function fetchUrlData($uri)
    {
        $pattern = '/{([a-z]+)}/';
        preg_match_all($pattern, $uri, $matches);
        

        $routeUrlExplode = explode('/', $uri);
        $clientUrlExplode = explode('/', $_SERVER['PATH_INFO']);
        $urlUserData = array_values(array_diff($clientUrlExplode,$routeUrlExplode));
        
        $uriNameWithValue = [];
        
        if(count($urlUserData)>0){
            foreach($matches[1] as $key => $uriName) {
                $uriNameWithValue[$uriName] = $urlUserData[$key];
            }
        }
        return $uriNameWithValue;
    }
    
    

    public static function all($uri, $controllerName){
        $namespacePath = 'App\\Controllers\\';
        $controller = $namespacePath . $controllerName;
        
        $controllerReflection = new \ReflectionClass($controller);
    
    
        $methods = $controllerReflection->getMethods();
        $methodName = '';
        $methods = array_map(function($obj){
          return strtolower($obj->name);
        },$methods);
        
        if(!in_array($methodName = static::getUserClientMethod($uri),$methods)){
            throw new UnrecognizeMethodException();
        }
        
        $methodValue = static::fetchUrlDataAll($uri);
        
        if( $controllerReflection->hasMethod($methodName) &&
            $controllerReflection->getMethod($methodName)->isPublic() &&
            static::checkForHttpMethod($controllerReflection->getMethod($methodName)->getDocComment())
        ){
            
            if(!empty($methodValue)){
                (new $controller)->$methodName($methodValue);
            } else {
                (new $controller)->$methodName();
            }
        }

    }
    
    private static function getUserClientMethod($uri)
    {
        $userClientUrl = $_SERVER['PATH_INFO'];
        
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
    
    private static function checkForHttpMethod($getDocComments)
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
    
    private static function fetchUrlDataAll($uri)
    {
        $userClientUrl = $_SERVER['PATH_INFO'];
        $urlClient = substr_replace($userClientUrl,'',0,strlen($uri));
        $urlValues = explode('/',$urlClient);
        
        // because there is an empty value after explode, we don't need it, so we get rid of it
        array_shift($urlValues);
        
        // we get rid of the method name
        array_shift($urlValues);
        
        return $urlValues;
    }
    
    
}
