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

/**
 * Class Routes
 * This is where all routes stored
 * @package SmartRouting
 */
class Routes extends AbstractRouteCollection
{

    /**
     * @var
     */
    private static $instance;
    /**
     * @var array
     */

    protected static $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
        'PATCH' => array()
    );

    /**
     * Routes constructor.singleton
     */
    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Routes();
        }
        return self::$instance;
    }

    /**
     * Add route
     * @param $name
     * @param $pattern
     * @param $controller
     * @param string $method
     * @throws RoutingException
     */
    public static function add($name, $pattern, $controller, $method = 'GET')
    {
        $meth = strtoupper($method);
        var_dump($meth);
        var_dump(self::$routes);
        if (array_key_exists($meth, self::$routes)) {
            foreach (self::$routes as $m => $routeName)
                if (!($name === $routeName)) {
                    self::$routes[$meth][$name] = array(
                        'pattern' => $pattern,
                        'controller' => $controller,
                    );
                    self::saveRoutes();
                } else {
                    throw new RoutingException("Route $name already exists");
                }

        } else {
            throw new RoutingException("Unsupported method:  $method");
        }

    }

    /**
     * Save routes to file
     */
    public static function saveRoutes()
    {
        $routesFile = dirname(__FILE__) . '/Routes/routes.php';
        file_put_contents($routesFile, '<?php return ' . var_export(self::$routes, true) . ";\n");
    }

    /**
     * Delete route
     * @param $name
     * @return bool|void
     */
    public static function deleteRoute($name)
    {
        foreach (self::$routes as $method => $route) {
            if (array_key_exists($name, $route)) {
                unset (self::$routes[$method][$name]);
            }
        }
        return true;
    }

    /**
     * Read routes from file
     */
    public static function readRoutes($path)
    {
        self::$fileRoutes = dirname(dirname(__DIR__)) . $path;
        self::$routes = include_once self::$fileRoutes;
    }

    /**
     * Get routes array
     * @return array
     */

    public static function getRoutes()
    {
        return self::$routes;
    }
}