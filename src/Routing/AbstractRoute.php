<?php

namespace SmartRouting\Routing;


abstract class AbstractRoute
{
    abstract public function findRoute($path, $method);

    abstract public function buildRoute($name, array $params = array(), $absolute = false);
}

