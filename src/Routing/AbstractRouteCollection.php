<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 13.02.16
 * Time: 17:03
 */

namespace SmartRouting\Routing;


abstract class AbstractRouteCollection
{
    abstract public function add($name, $pattern, $controller, $method = 'GET');

    abstract public function deleteRoute($name);

    abstract public function getRoutes();

    abstract protected function readRoutes();

    abstract protected function saveRoutes();
}