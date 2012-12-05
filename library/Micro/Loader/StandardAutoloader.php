<?php

namespace Micro\Loader;

class StandardAutoloader
{
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }
    
    protected function transformClassNameToFilename($class)
    {
        $matches = array();
        preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $class, $matches);

        $class     = (isset($matches['class'])) ? $matches['class'] : '';
        $namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';
        
        $path = str_replace('\\', '/', $namespace);
        
        if ($path == 'Application/Model/')
            $path = 'models/';
        
        return $path
             . str_replace('_', '/', $class)
             . '.php';
    }
    
    public function autoload($class)
    {
        $result = $this->loadClass($class);
        if (!$result)
            throw new \RuntimeException('Class "' . $class . '" not found');

        return $result;
    }
    
    public function loadClass($class)
    {
        $filename     = $this->transformClassNameToFilename($class);
        $resolvedName = stream_resolve_include_path($filename);
        
        if ($resolvedName !== false) {
            return include $resolvedName;
        }
        
        return false;
    }
}