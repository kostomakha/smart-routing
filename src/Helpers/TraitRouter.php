<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/2/16
 * Time: 11:26 AM
 */

namespace Itcourses\Core\Router\Helpers;


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
        if (!empty($route)) {
            $this->controller = array_shift($route);
            if(!empty($route)) {
                $this->action = array_shift($route);
            } else {
                $this->action = 'index';
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