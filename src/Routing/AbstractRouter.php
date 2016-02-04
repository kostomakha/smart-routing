<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 1/27/16
 * Time: 11:26 AM
 */

namespace SmartRouting\Routing;
use SmartRouting\Routing\Helpers\TraitRouter;
abstract class AbstractRouter
{
    use TraitRouter;

    protected $request;
    protected $method;
    protected $uri;
    protected $routes;
    protected $controller;
    protected $action;
    protected $params = [];

    /**
     * Router constructor.
     * @param $request
     */

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->uri = $this->request->getUri();
        var_dump($this->uri);

        $this->method = $this->request->getMethod();
        var_dump($this->method);
    }
}