<?php

namespace SmartRouting\Routing;
use SmartRouting\Routing\Helpers\TraitRoute;

/**
 * Class Route
 * @package Itcourses\Core
 */
abstract class AbstractRoute
{
    use TraitRoute;
    /**
     * Array with predefined methods
     * @var array
     */
    protected $params = [];

    protected $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
        'PATCH' => array()
    );
    /**
     * Array with patterns for preg_match
     * @var array
     */
    protected $filter = array(
        'number' => '[0-9]+',
        'string' => '[a-zA-Z]+',
        'any' => '[a-zA-Z0-9\-_]+'
    );

    protected $pattern = array(
//        'num' => 'number',
//        'string' => 'string',
//        'any' => 'any',
        'id' => 'number',
        'name' => 'string',
        'age' => 'any',
        'article' => 'any',
        'category' => 'any',
        'course' => 'any'
    );


    public function __construct()
    {
        $this->add('default','/','Main:index', 'GET');
        $this->readRoutes();
        //var_dump($this->routes);
    }

    /**
     * read routes from file
     */


    //abstract public function readRoutes();

    /**
     * @return mixed
     */
    //abstract public function saveRoutes();

}
