<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 1/27/16
 * Time: 11:26 AM
 */

namespace Routing;

class Router
{
    protected $request;
    protected $method;
    protected $uri;
    protected $routes;

    protected $controller;

    protected $action;

    protected $params = [];



    /**
     * Router constructor.
     * @param $request
     */

    public function __construct($request)
    {
        $this->request = $request;

        $this->uri = $this->request->getUri();
        var_dump($this->uri);

        $this->method = $this->request->getMethod();
        var_dump($this->method);
    }

    /**
     * @param $route
     */
    public function getRoute($route)
    {
        $this->routes = $route;

        $route = $this->routes->finedRoute($this->uri, $this->method);
        var_dump($route);
        if (!empty($route)) {
            $this->controller = array_shift($route);
            if(!empty($route)) {
                $this->action = array_shift($route);
            }
            if(!empty($route)) {
                $this->params = $route ? array_values($route) : [];
            }

        } else {
            $this->controller = 'Main';
            $this->action = 'index';
        }
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    public function  getParams()
    {
        return $this->params;
    }
}