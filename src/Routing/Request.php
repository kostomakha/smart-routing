<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/8/16
 * Time: 6:03 PM
 */

namespace SmartRouting\Routing;


class Request
{
    public $method = 'GET';

    public $uri = 'Home';

    public function __construct()
    {
        $this->uri = new Uri();
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri(){
        return $this->uri;
    }
}