<?php

namespace Micro\Application;

use Micro\Http;
use Micro\Exception;
use Micro\View;
use Micro\Db\Adapter\Adapter as DbAdapter;
use Micro\Application\Router\RouteStack;

class Application
{

    protected $request;
    protected $response;
    protected $view;
    protected $routeStack;
    protected $dbAdapter;
    protected $controllers = array();

    public function __construct($config)
    {
        $this->config     = $config;
        $this->request    = new Http\Request();
        $this->response   = new Http\Response();
        $this->layout     = new View\Layout();
        $this->routeStack = new RouteStack();

        $this->response->setVersion($this->request->getVersion());
        $this->layout->setScript('index');
    }

    /**
     * 
     * @return Micro\Http\Response
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * 
     * @return Micro\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * 
     * @return Micro\View\View
     */
    public function getView()
    {
        if (null === $this->view)
            $this->view = new View\View();
        
        return $this->view;
    }
    
    /**
     * 
     * @return Micro\View\layout
     */
    public function getLayout()
    {
        return $this->layout;
    }
    
    public function getDb()
    {
        if (null === $this->dbAdapter) {
            if (!isset($this->config['db']))
                throw new \RuntimeException('Database not configured');
            
            $this->dbAdapter = new DbAdapter($this->config['db']);
        }
        
        return $this->dbAdapter;
    }

    public function run()
    {
        $this->loadControllers();
        
        try {
            $route = $this->routeStack->match($this->request);
            
            if (!$route)
                throw new Exception\PageNotFoundException();
            
            $result = call_user_func($route->getController(), $this);
            
        } catch (Exception\PageNotFoundException $e) {
            if (isset($this->controllers['pageNotFound'])) {
                $this->response->setStatusCode(404);
                $this->getView()->setScript('error/404');
                $result = call_user_func($this->controllers['pageNotFound'], $this);
            }
            
        } catch (\Exception $e) {
            $this->response->setStatusCode(500);
            $this->getView()->setScript('error/500');
            $result = call_user_func($this->controllers['internalError'], $this, $e);
        }
        
        try {
            if (null != $this->view) {
                $content = $this->view->render($result);

                $this->layout->setPart('content', $content);
                $output = $this->layout->render();
                $this->response->appendBody($output);
            }
        } catch (\Exception $e) {
            
        }

        $this->response->sendHeaders()
                       ->outputBody();
    }
    
    protected function loadControllers()
    {
        $flags = \FilesystemIterator::SKIP_DOTS & \FilesystemIterator::KEY_AS_PATHNAME;
        $controllersFiles = new \FilesystemIterator('controllers', $flags);
        foreach ($controllersFiles as $file)
            include $file;
    }

    public function map($route, $controller)
    {
        $this->routeStack->addRoute($route, $controller);
        return $this;
    }

    public function setController($name, $controller)
    {
        $this->controllers[$name] = $controller;
    }

}