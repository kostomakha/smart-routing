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
    public static function add($name, $pattern, $controller, $method = 'GET')
    {

    }

    public static function deleteRoute($name) {

    }

    abstract public function getRoutes();

    protected static function readRoutes()
    {

    }

    protected static function saveRoutes()
    {

    }

}