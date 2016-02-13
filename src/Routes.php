<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 13.02.16
 * Time: 17:05
 */

namespace SmartRouting;


use SmartRouting\Routing\AbstractRouteCollection;
use SmartRouting\Routing\Exception\RoutingException;

class Routes extends AbstractRouteCollection
{
    private static $instance;



    protected static $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
        'PATCH' => array()
    );



    private  function __construct()
    {
    }
    public static function getInstance()
    {
        if( empty(self::$instance)){
            self::$instance = new Routes();
        }
        return self::$instance;
    }

    public static function add($name, $pattern, $controller, $method = 'GET')
    {

        $method = strtoupper($method);

        if (array_key_exists($name, self::$routes[$method])){
            throw new RoutingException("Route $name already exists");
        }

        self::$routes[$method][$name] = array(
            'pattern' => $pattern,
            'controller' => $controller,
        );

        self::saveRoutes();

    }

    public static function deleteRoute($name)
    {
        foreach (self::$routes as $method => $route) {
            if(array_key_exists($name, $route)){
                unset (self::$routes[$method][$name]);
            }
        }
    }
    private function validateArguments(){
        $arguments = func_get_args();
        foreach ($arguments as $k => $v) {
            if(is_string($v)) {
                return true;
            } else {
                return false;
            }
        }
    }

    protected static function readRoutes()
    {
        $routesFile = dirname(__FILE__). '/Routes/routes.php';
        self::$routes = include $routesFile;
    }

    public static function saveRoutes()
    {
        $routesFile = dirname(__FILE__). '/Routes/routes.php';
        file_put_contents ( $routesFile , '<?php return '.var_export( self::$routes, true ).";\n");
    }

    public function getRoutes()
    {
        return self::$routes;
    }

//    public function __destruct()
//    {
//        $this->saveRoutes();
//    }
}