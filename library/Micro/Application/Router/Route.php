<?php

namespace Micro\Application\Router;

class Route
{
    protected $controller;
    protected $route;
    protected $parts = array();
    protected $params = array();
    
    public function __construct($route, $controller)
    {
        $route = rtrim($route, '/');
        
        $this->route = $route;
        $this->controller = $controller;
        $this->parts = explode('/', $route);
    }

    public function setController(\Closure $controller)
    {
        $this->controller = $controller;
        return $this;
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function match($uri)
    {
        $parts = explode('/', $uri);
        
        $i = 0;
        foreach($parts as $part) {
            if (!isset($this->parts[$i]))
                return false;
            
            if (isset($this->parts[$i][0]) && ($this->parts[$i][0] == ':')) {
                $name = substr($this->parts[$i], 1);
                $this->params[$name] = $part;
                $i++;
                continue;
            }
            
            if ($part !== $this->parts[$i])
                return false;
            
            $i++;
        }
        
        if ($i < count($this->parts))
            return false;
        
        return true;
    }
}
