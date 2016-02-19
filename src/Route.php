<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 2/4/16
 * Time: 6:50 PM
 */

namespace SmartRouting;

use SmartRouting\Routing\AbstractRoute;

use SmartRouting\Routing\Exception\RoutingException;

class Route extends AbstractRoute
{
    /**
     * @var array saving params from uri if
     */
    protected $params = [];

    /**
     * @var
     */
    protected $path;

    /**
     * @var route name
     */
    protected $name;

    /**
     * @var string sitehostname
     */
    protected $hostname = 'http://www.itcourses.com';

    /**
     * @var array here we put all routes from Routes
     */
    protected $routes = [];

    /**
     * @var array default filtes for matching
     */
    protected $filter = array(
        'num' => '[0-9]+',
        'string' => '[a-zA-Z]+',
        'any' => '[a-zA-Z]+'
    );

    public function __construct()
    {
        $this->routes = Routes::getRoutes();
    }

    /**
     * @param $path
     * @param $method
     * @return array|bool
     */
    public function findRoute($path, $method)
    {
        $method = strtoupper($method);

        //select all routes which corresponded to $method
        $routes = $this->routes[$method];

        $queryArray = explode('/', trim($path, '/'));

        foreach ($routes as $name => $route) {

            //if patter equles path we done
            if (in_array($path, $route)) {
                return $this->parseController(end($route));
            }

            $pattern = $route['pattern'];
            $patternArray = explode('/', trim($pattern, '/'));

            //check, have we some optionals parameters
            if (!strpbrk($pattern, '?') && strpbrk($pattern, '(')) {


                /**
                 * Replace all params for proper pregmatch that we stored in our $filters property
                 * Call a replaceFilter() method on $patternArray values
                 */

                $patternArrayFiltered = array_map(array($this, 'replaceForFilter'), $patternArray);
                $patternMatcher = '/' . implode('/', $patternArrayFiltered);

                //Do match
                preg_match('\'' . $patternMatcher . '\'', $path, $matched);

                //If matched lat's take params
                if ($matched) {

                    //Call a setParamsNmaes() method on every array value
                    $this->saveQueryParameters($patternArray, $queryArray);
                    return $this->parseController(end($route));
                }

            //If pattern have some optional parameters
            } elseif (strpbrk($pattern, '?')) {
                $patternMatcherArray = [];

                /**
                 * build pattern match
                 */
                foreach ($patternArray as $k => $v) {
                    if (strpbrk($v, '?')) {
                        $patternMatcherArray[$k] = '((\/)?([a-zA-Z]+)?)?';
                    } elseif (strpbrk($v, '(')) {
                        $patternMatcherArray[$k] = $this->replaceForFilter($v);
                    } elseif (!strpbrk($v, '(')) {
                        $patternMatcherArray[$k] = '\/' . $v . '\/';
                    }
                }

                //Do we have parameters
                $patternMatcher = implode('', $patternMatcherArray);
                preg_match('\'' . $patternMatcher . '\'', $path, $matched);

                if ($matched) {
                    //save params for optional routes
                    $this->saveQueryParameters($patternArray, $queryArray);
                    return $this->parseController(end($route));
                }
            }
        }

        return false;
    }

    /**
     * This method replace all masks for preg_match from $filters
     * @param $a
     * @return mixed
     */
    private function replaceForFilter($a)
    {
        if (strpbrk($a, '?')) {
            $filterType = substr($a, strpos($a, ':') + 1, -2);
            return $a = str_replace($a, $this->filter[$filterType], $a);
        } elseif (strpbrk($a, ':') && !strpbrk($a, '?')) {
            $filterType = substr($a, strpos($a, ':') + 1, -1);
            return $a = str_replace($a, $this->filter[$filterType], $a);
        } elseif (strpbrk($a, '(')) {
            return $a = str_replace($a, $this->filter['any'], $a);
        }

        return $a;

    }

    /**
     * Builed route with or without prams
     * @param $name - what route we select
     * @param array $params - optional params for route
     * @param int $absolute - key build absolute or n
     * @return bool|string
     * @throws RoutingException
     */
    public function buildRoute($name, array $params = array(), $absolute = null)
    {
        $this->name = $name;
        $pattern = $this->getRoutePattern($this->name);
        var_dump($pattern);
        if ($params) {
            $patternArray = explode('/', trim($pattern, '/'));
            $patternArrayFilter = array_map(array($this, 'replaceForFilter'), $patternArray);
            $paramsNameArray = array_map(array($this, 'setParamsNames'), $patternArray);

            foreach ($paramsNameArray as $k => $v) {
                var_dump($v);
                if (array_key_exists($v, $params)) {
//                    $key = array_search($k, $paramsNameArray);
//                    var_dump($key);
                    preg_match('/' . $patternArrayFilter[$k] . '/', $params[$v], $matched);
                    if ($matched) {
                        $paramsNameArray[$k] = $params[$v];
                    } else {
                        throw new RoutingException("Unexpected parameter $v");
                    }

                } else {
                    if (!strpbrk($patternArray[$k], ')')) {
                        $paramsNameArray[$k] = $v;
                    } elseif (strpbrk($patternArray[$k], '?')) {
                        unset($paramsNameArray[$k]);
                    } else {
                        throw new RoutingException("Not not enough parameters");
                    }

                }
                var_dump($this->buildPath($paramsNameArray, $absolute));


            }
            return $this->buildPath($paramsNameArray, $absolute);

        } elseif (empty($params) && !strpbrk($pattern, '(')) {

            return $this->buildPath($pattern, $absolute);

        } else {

            throw new RoutingException("Not not enough parameters");

        }
    }

    /**
     * @param $name
     * @param $preg
     */
    public function addFilter($name, $preg)
    {

        $this->filter[$name] = $preg;

    }

    /**
     * @param $a
     * @return string
     */
    private function setParamsNames($a)
    {
        if (strpbrk($a, ':')) {
            return $a = substr($a, 1, strpos($a, ':') - 1);
        } elseif (strpbrk($a, '(')) {
            return $a = substr($a, 1, -1);
        }
        return $a;
    }

    /**
     * Reteruna controller action, and params
     * @param $data
     * @return array
     */
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

    /**
     * Retern route pattern by it's name
     * @param $name
     * @return string
     */
    private function getRoutePattern($name)
    {
        foreach ($this->routes as $method => $routeName) {
            if (array_key_exists($name, $routeName)){
                return $tempArray = trim($routeName[$name]['pattern'], '/');
            }
        }

        return false;
    }

    /**
     * Save paremters to $params array if uri been matched
     * @param $patternArray
     * @param $queryArray
     * @return array $this->params or false
     * @internal param $patteren
     */
    private function saveQueryParameters($patternArray, $queryArray)
    {
        $paramsNameArray = array_map(array($this, 'setParamsNames'), $patternArray);
        foreach ($patternArray as $k => $v) {
            if (strpbrk($v, '(')) {
                if (array_key_exists($k, $queryArray)) {
                    return $this->params[$paramsNameArray[$k]] = $queryArray[$k];
                }
            }
        }
        return false;
    }

    /**
     * Build url from buildRoute result
     * @param $data
     * @param $absolute
     * @return bool|string
     */
    protected function buildPath($data, $absolute = null)
    {
        if ($absolute) {
            if (is_array($data)) {
                $path = $this->hostname . '/' . implode('/', $data);
                return $path;
            } elseif (is_string($data)) {
                return $path = $this->hostname . $data;
            }
        } else {
            if (is_array($data)) {
                $path = '/' . implode('/', $data);
                return $path;
            } elseif (is_string($data)) {
                return $path = $data;
            }
        }

        return false;
    }
}
