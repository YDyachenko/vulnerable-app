<?php

namespace Micro\View;

class View
{
    protected $script;
    protected $pathPrefix = 'view';
    
    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }
    
    public function setPathPrefix($pathPrefix)
    {
        $this->pathPrefix = $pathPrefix;
        return $this;
    }
    
    protected function getScriptPath()
    {
        return $this->pathPrefix . '/scripts/' . $this->script . '.phtml';
    }


    public function render($params)
    {
        if (is_array($params))
            extract($params);
        
        ob_start();
        include $this->getScriptPath();
        $return = ob_get_clean();
        
        return $return;
    }
}