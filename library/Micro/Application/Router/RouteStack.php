<?php

namespace Micro\Application\Router;

use Micro\Http\Request;
use Micro\Application\Router\Route;

class RouteStack
{
    protected $routes = array();
    
    public function addRoute($route, $controller)
    {
        $route = new Route($route, $controller);
        $this->routes[] = $route;
    }
    
    public function match(Request $request)
    {
        $uri = rtrim($request->getUri(), '/');
        
        foreach ($this->routes as $route) {
            if ($route->match($uri)) {
                $this->setRequestParams($request, $route->getParams());
                return $route;
            }
        }
        
        return false;
    }
    
    protected function setRequestParams(Request $request, $params)
    {
        foreach ($params as $name => $value) {
            $request->setParam($name, urldecode($value));
        }
    }
}
