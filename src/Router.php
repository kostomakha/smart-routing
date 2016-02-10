<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 2/4/16
 * Time: 6:51 PM
 */

namespace SmartRouting;
use SmartRouting\Basic\AbstractRouter;
use SmartRouting\Request;

class Router extends AbstractRouter
{
    protected $request;
    protected $method;
    protected $uri;
    protected $routes;


    /**
     * Router constructor.
     * @param $request
     */

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->uri = $this->request->getUri()->getPath();
        var_dump($this->uri);

        $this->method = strtoupper($this->request->getMethod());
        //var_dump($this->method);
    }

    public function getRoute($route)
    {
        $this->routes = $route;

        $route = $this->routes->findRoute($this->uri, $this->method);
        var_dump($route);

        if ($route != 0 && is_array($route)) {

            $this->setResult($route);

        } else {
            $this->controller = 'DefaultController';
            $this->action = 'ActionIndex';
        }
    }

    public function route($name)
    {
        $this->setResult($this->routes->route($name));
        return $this;
    }

    protected function setResult($routeArray)
    {
        $this->controller = $this->formatResult(array_shift($routeArray)) . 'Controller';
        if ($routeArray) {
            $this->action = 'action' . $this->formatResult(array_shift($routeArray));
            if ($routeArray) {
                foreach ($routeArray as $k => $v) {
                    $this->params[$k] = $v;
                }
            }else {
                $this->params = [];
            }
        } else {
            $this->action = 'actionIndex';
        }
    }

    protected function formatResult($data) {
        return $formated = ucfirst(strtolower($data));
    }
}