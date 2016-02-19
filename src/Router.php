<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 2/4/16
 * Time: 6:51 PM
 */

namespace SmartRouting;

use SmartRouting\Routing\AbstractRouter;


class Router extends AbstractRouter
{
    protected $request;
    protected $method;
    protected $uri;
    public $route;

    /**
     * Router constructor.
     * @param $request
     */

    public function __construct(Request $request)
    {
        //Get data from HttpRequest
        $this->request = $request;
        $this->uri = $this->request->getUri()->getPath();
        $this->method = strtoupper($this->request->getMethod());
        $this->route = new Route();
    }

    /**
     * @return $this
     */
    public function getRoute()
    {
        $route = $this->route->findRoute($this->uri, $this->method);

        if ($route != 0 && is_array($route)) {
            $this->setResult($route);
            return $this;
        } else {
            $this->controller = 'DefaultController';
            $this->action = 'actionIndex';
            return $this;
        }
    }


    /**
     * Buiild url by name and params
     * @param $name - of the route which will be built
     * @param array $params which will be sent to the route pattern
     * @param $absolute - default value true if false url built without  hostname
     * @return $this
     * @throws Routing\Exception\RoutingException
     */
    public function buildRoute($name, array $params, $absolute = true)
    {
        return $this->route->buildRoute($name, $params, $absolute);
    }

    /**
     * @param $routeArray
     */
    private function setResult($routeArray)
    {
        $this->controller = $this->formatResult(array_shift($routeArray)) . 'Controller';
        if ($routeArray) {
            $this->action = 'action' . $this->formatResult(array_shift($routeArray));
            if ($routeArray) {
                foreach ($routeArray as $k => $v) {
                    $this->params[$k] = $v;
                }
            } else {
                $this->params = [];
            }
        } else {
            $this->action = 'actionIndex';
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function formatResult($data)
    {
        return $formated = ucfirst($data);
    }
}