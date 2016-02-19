<?php

namespace SmartRouting\Routing;


use SmartRouting\Routes;

abstract class AbstractRoute
{
    protected $routesCollection;

    public function __construct()
    {
        $this->routesCollection = Routes::getInstance();
    }

    abstract public function findRoute($path, $method);

    abstract public function buildRoute($name, array $params = array(), $absolute = false);
}

