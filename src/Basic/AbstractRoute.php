<?php

namespace SmartRouting\Basic;

abstract class AbstractRoute
{
    protected $params = [];

    protected $filter = array(
        'num' => '[0-9]+',
        'string' => '[a-zA-Z]+',
        'any' => "[a-zA-Z0-9\-_]+"
    );

    protected $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
        'PATCH' => array()
    );

    abstract public function add($name, $pattern, $controller, $method = 'GET');

    public function addPattern($name, $filter){
        $this->pattern[$name] = $filter;
    }

    abstract public function findRoute($path, $method);


    /**
     * @param $data
     * @return array
     */
    protected function parseController($data)
    {
        if (strpos($data, ':')) {
            $controllerArray = explode(':', $data);
            $controllerArray = $controllerArray + $this->params;
            return $controllerArray;
        }else {
            $controllerArray[0] = $data;
            return $controllerArray;
        }
    }

    public function deleteRoute($name)
    {
        foreach ($this->routes as $method => $route){
            if(array_key_exists($name, $route)){
                unset($this->routes[$method][$name]);
            }

        }
    }

    public function route($name)
    {
        foreach ($this->routes as $method => $route) {
            if(array_key_exists($name, $route)) {
                return $this->parseController(end($route[$name]));
            }
        }
    }



    protected function buildPath($data) {
        if (is_array($data)){
            $path = '/' . implode('/', $data);
            return $path;
        } elseif (is_string($data)) {
            $path = $data;
            return $path;
        }
    }

    protected function buildAbsolutePath($data) {
        if (is_array($data)){
            $path = $this->base . '/' . implode('/', $data);
            return $path;
        } elseif (is_string($data)) {
            $path = $this->base . $data;
            return $path;
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    abstract protected function readRoutes();

    abstract protected function saveRoutes();
}
