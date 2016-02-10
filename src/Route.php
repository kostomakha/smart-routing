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
     * read routes from file
     */
    public function add($name, $pattern, $controller, $method = 'GET')
    {

        $method = strtoupper($method);

        if (array_key_exists($name, $this->routes[$method])){
            $this->deleteRoute($name);

        }

        $this->routes[$method][$name] = array(
            'pattern' => $pattern,
            'controller' => $controller
        );

        $this->saveRoutes();
        return true;
    }

    public function findRoute($uri, $method)
    {
        $routes = $this->routes[$method];
        // var_dump($routes);
        $query = explode('/', trim($uri, '/'));

        foreach ($routes as $name => $route){
            var_dump($route);
            if(in_array($uri, $route)) {
                return $this->parseController(end($route));
            }

            if(strpos($route['pattern'], '(')) {
                $patternArray = explode('/', trim($route['pattern'], '/'));

                foreach ($patternArray as $key => $value) {
                    $patternArrayTrimed[$key] = trim($value, '()');
                }

                var_dump($patternArrayTrimed);

                $patternArrayFliped = array_flip($patternArrayTrimed);

                //      echo "patternArrayFliped";
                //       var_dump($patternArrayFliped);
                unset($patternArrayTrimed);
                //        echo "patternArrayTrimed";
                //       var_dump($patternArrayTrimed);

                foreach ($patternArrayFliped as $k => $value) {
                    //        echo "This is key if $k </br>";
                    if (!array_key_exists($k, $this->pattern)) {
                        $patternArrayFliped[$k] = $k;
                    } else {
                        $patternArrayFliped[$k] = $this->filter[$this->pattern[$k]];
                        //                          echo "This is key $k";
                    }

                }
                //       echo "patternArrayFliped new </br>";
                //       var_dump($patternArrayFliped);
                $matchPattern = '/' . implode('/', $patternArrayFliped);
                //      echo "This is $matchPattern</br>";

                preg_match('\'' . $matchPattern . '\'', $uri, $matched);
                //        var_dump($matched);
                if ($matched) {
                    foreach ($patternArray as $k => $v){
                        //  echo "Params key $k , value $v";
                        if (strrpos($v, ')')){
                            //$tempKey = array_search($v, $patternArray);
                            $v = trim($v, '()');
                            //  echo "query  key $k , value $v";
                            $this->params[$v] = $query[$k];

                            //return $this->parseController(end($route));
                        }
                        //$this->params = [];
                        //               var_dump($this->params);
                    }
                    return $this->parseController(end($route));
                }
                return $this->parseController($this->route('error'));
            }
        }
        return true;
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