<?php

namespace Micro\View;

//use Micro\View\View;

class Layout
{

    protected $view;
    protected $script;
    protected $parts = array();

    protected function getView()
    {
        if (null === $this->view) {
            $this->view = new View();
        }

        return $this->view;
    }

    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }

    public function setPart($name, $value)
    {
        $this->parts[$name] = $value;
        return $this;
    }

    public function render()
    {
        return $this->getView()->setScript($this->script)
                               ->setPathPrefix('layout')
                               ->render($this->parts);
    }

}