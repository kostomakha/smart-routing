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
    public function add($name, $pattern, $defaultParams = [], $controller, $method = 'GET')
    {

        $method = strtoupper($method);

        if (array_key_exists($name, $this->routes[$method])){
            $this->deleteRoute($name);
        }

        $this->routes[$method][$name] = array(
            'pattern' => $pattern,
            'defaultParams' => $defaultParams,
            'controller' => $controller,
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
            elseif(strpbrk($pattern, '?')) {
                foreach ($patternArray as $k => $v) {
                    echo "$k ====== $v";
                    if (strpbrk($v, '?')) {
                        $patternMatcherArray[$k] = '((\/)?([a-zA-Z]+)?)?';

                    } elseif (strpbrk($v, '(')) {
                        $patternMatcherArray[$k] = $this->replaceForFilter($v);
                    } elseif  (!strpbrk($v, '(')) {
                        $patternMatcherArray[$k] = '\/' . $v . '\/';
                    }
                }

                $patternMatcher2 = implode('', $patternMatcherArray);

                preg_match('\'' . $patternMatcher2 . '\'', $path, $matched);

                if ($matched) {

                    $paramsNameArray = array_map(array($this, 'setParamsNames'), $patternArray);

                    foreach ($patternArray as $k => $v) {

                        if (strpbrk($v, '(')) {
                            if(array_key_exists($k, $queryArray)){
                                $this->params[$paramsNameArray[$k]] = $queryArray[$k];
                            }
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
        } elseif (strpbrk($a, '(')) {
            return $a = substr($a, 1 , -1);
        }
        return $a;
    }

//    public function buildRoute($name, array $params = array(), $absolute = false)
//    {
//        echo "$name";
//        var_dump($name);
//        $pattern = $this->getRoutePattern($name);
//        var_dump($pattern);
//        $routeDefaultParams = $this->getRouteDefaultParams($name);
//        var_dump($routeDefaultParams);
//
//        if ($params && $routeDefaultParams) {
//            $params = array_replace_recursive($routeDefaultParams, $params);
//
//        } elseif (!$params) {
//
//        } elseif (!$routeDefaultParams) {
//
//        } else {
//            echo "не достаточно параметров";
//        }
//
//    }

    protected function parseController($data)
    {
        if (strpos($data, ':')) {
            $controllerArray = explode(':', $data);
            $controllerArray = $controllerArray + $this->params;
            return $controllerArray;
        } else {
            $controllerArray[0] = $data;
            return $controllerArray;
        }
    }

    private function getRoutePattern($name)
    {
        foreach ($this->routes as $method => $routeName) {

            var_dump($routeName);
            return $tempArray = trim($routeName[$name]['pattern'], '/');
        }
    }

    private function getRouteDefaultParams($name)
    {
        foreach ($this->routes as $method => $routeName) {
            var_dump($routeName);
            return $tempArray = $routeName[$name]['defaultParams'];
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