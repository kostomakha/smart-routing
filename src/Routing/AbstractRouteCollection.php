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
    abstract public static function add($name, $pattern, $controller, $method = 'GET');

    abstract public static function deleteRoute($name);

    abstract public function getRoutes();

    abstract protected static function readRoutes();

    abstract protected static function saveRoutes();
}