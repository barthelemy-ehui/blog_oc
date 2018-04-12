<?php
namespace App;

class Route
{
    const GET_METHOD  = "GET";
    const POST_METHOD  = "POST";
    
    
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
            $heystack = preg_replace('/\/?\{[[:alnum:]]+\}\/?/','',$routeUrl);
            return strpos($clientUrl,$heystack) !== false && $http_method === $_SERVER['REQUEST_METHOD'];
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
}
