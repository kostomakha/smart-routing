<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 1/27/16
 * Time: 11:26 AM
 */

namespace SmartRouting\Routing;

abstract class AbstractRouter
{
    protected $controller;
    protected $action;
    protected $params = [];


    abstract public function getRoute();

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function  getParams()
    {
        return $this->params;
    }

}