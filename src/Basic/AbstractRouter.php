<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 1/27/16
 * Time: 11:26 AM
 */

namespace SmartRouting\Basic;

abstract class AbstractRouter
{
    protected $controller;
    protected $action;
    protected $params = [];

    /**
     * @param $route
     */
    abstract public function getRoute($route);

    abstract public function route($name);
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