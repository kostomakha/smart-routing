<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 2/4/16
 * Time: 6:50 PM
 */

namespace SmartRouting;
use SmartRouting\Basic\AbstractRoute;

class Route extends AbstractRoute
{
    private $routeFile = '/home/roach/Projects/nix6/smart-routing/src/routes/routes.php';
    private $base = 'http://www.example.com';

    protected $pattern = array(
        'id' => 'number',
        'name' => 'string',
        'age' => 'any',
        'article' => 'any',
        'category' => 'any',
        'course' => 'any'
    );


    public function __construct()
    {
        //$this->readRoutes();
        $this->add('default','/','Main:index', 'GET');
        //var_dump($this->routes);
    }

    /**
     * @param $name
     * @param $pattern
     * @param $controller
     * @param array $params
     * @param string $method
     * @return bool
     */
    public function add($name, $pattern, $controller, $params = array(), $method = 'GET')
    {

        $method = strtoupper($method);

        if (array_key_exists($name, $this->routes[$method])){
            $this->deleteRoute($name);
        }

        $this->routes[$method][$name] = array(
            'pattern' => $pattern,
            'controller' => $controller
        );

        //$this->saveRoutes();
        return true;
    }

    public function findRoute($path, $method)
    {
        var_dump($path);
        $routes = $this->routes[$method];
        var_dump($routes);
        $queryArray = explode('/', trim($path, '/'));

        foreach ($routes as $name => $route) {
            echo 'Route';

            if (in_array($path, $route)) {

                return $this->parseController(end($route));
            }
            $pattern = $route['pattern'];

            $patternArray = explode('/', trim($pattern, '/'));

            if(!strpbrk($pattern, '?') && strpbrk($pattern, '(')) {

                $patternArrayFiltered = array_map(array($this, 'replaceForFilter'), $patternArray);

                $patternMatcher = '/' . implode('/', $patternArrayFiltered);

                preg_match('\'' . $patternMatcher . '\'', $path, $matched);

                if ($matched) {

                    $paramsNameArray = array_map(array($this, 'setParamsNames'), $patternArray);

                    foreach ($patternArray as $k => $v){
                        if (strpbrk($v, '(')){
                            $this->params[$paramsNameArray[$k]] = $queryArray[$k];
                        }
                    }
                    return $this->parseController(end($route));
                }
            }
        }
    }

    private function replaceForFilter($a){
        if (strpbrk($a, ':')){
            $filterType = substr($a, strpos($a, ':') + 1, -1);
            return $a = str_replace($a, $this->filter[$filterType], $a);
        } elseif (strpbrk($a, '(')) {
            return $a = str_replace($a, $this->filter['any'], $a);
        }
        return $a;
    }

    private function setParamsNames($a){
        if (strpbrk($a, ':')){
            return $a = substr($a, 1 , strpos($a, ':')-1);
            //var_dump($a);
        } elseif (strpbrk($a, '(')) {
            return $a = substr($a, 1 , -1);
        }
        return $a;
    }

    public function buildRoute($name, array $params = array(), $absolute = false)
    {
        $patternArray = $this->getRoutePattern($name);

        if($params) {
            $tempUrl = $patternArray;

            foreach ($tempUrl as $k => $v) {
                if (strrpos($v, ')')) {
                    unset($tempUrl[$k]);
                }
            }
            $this->buildUrl($tempUrl, $absolute);
        }
    }

    private function getRoutePattern($name)
    {
        foreach ($this->routes as $method => $routeName) {
            if($name == $routeName){
                foreach ($routeName as $k => $v){
                    return $tempArray = explode('/', trim($this->routes['pattern'], '/'));
                }
            }
        }
    }

    protected function readRoutes()
    {
        $this->routes = include $this->routeFile;
    }

    protected function saveRoutes()
    {
        file_put_contents ( $this->routeFile , '<?php return '.var_export( $this->routes, true ).";\n");
    }
}