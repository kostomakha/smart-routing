<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/2/16
 * Time: 11:26 AM
 */

namespace SmartRouting\Routing\Helpers;


trait TraitRouter
{
    /**
     * @param $route
     */
    public function getRoute($route)
    {
        $this->routes = $route;

        $route = $this->routes->finedRoute($this->uri, $this->method);
        //var_dump($route);

            if ($route != 0 && is_array($route)) {
                $this->controller = $this->formatResult(array_shift($route)) . 'Controller';
                if ($route != 0) {
                    $this->action = 'action' . $this->formatResult(array_shift($route));
                    if ($route != 0 ) {
                        foreach ($route as $k => $v) {
                            $this->params[$k] = $v;
                        }
                    }else {
                        $this->params = [];
                    }
                } else {
                    $this->action = 'actionIndex';
                }
            } else {
                $this->controller = 'DefaultController';
                $this->action = 'actionIndex';

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

    private function formatResult($data) {
        return $formated = ucfirst(strtolower($data));
    }
}
