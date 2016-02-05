<?php
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/2/16
 * Time: 11:46 AM
 */

namespace SmartRouting\Routing\Helpers;


trait TraitRoute
{
    public function add($name, $pattern, $controller, $method = 'GET')
    {
//        var_dump($name);
        if (array_key_exists($name, $this->routes[$method])){
            $this->deleteRoute($name);

        }

        $this->routes[$method][$name] = array(
            'pattern' => $pattern,
            'controller' => $controller
        );

        $this->saveRoutes();
    }

    public function addPattern($name, $filter){
        $this->pattern[$name] = $filter;
    }
    /**
     * This method is callable by Router.
     *
     * @param $uri - get from request through Router
     * @param $method - get from request through Router
     * @return array which consist from controller and method specified in routes array
     */
    public function finedRoute($uri, $method)
    {
        $routes = $this->routes[$method];
       // var_dump($routes);
        $query = explode('/', trim($uri, '/'));

        foreach ($routes as $name => $route){
       //     var_dump($route);
            if(in_array($uri, $route)) {
                return $this->parseController(end($route));
            }

            if(strpos($route['pattern'], '(')) {
                $patternArray = explode('/', trim($route['pattern'], '/'));

                foreach ($patternArray as $key => $value) {
                    $patternArrayTrimed[$key] = trim($value, '()');
                }

       //         var_dump($patternArrayTrimed);

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
            }
        }
//        return $this->match($uri, $method);
    }
//    /**
//     *
//     *
//     * @param $uri
//     * @param $method
//     * @return array
//     */
//    private function match($uri, $method)
//    {
//
//
//            foreach ($this->routes[$method] as $route) {
//
//
//            }
//    }

    public function buildRoute($name, array $params = array(), $absoluteUrl = false)
    {
        foreach ($this->routes as $method => $routeName) {
            if (array_key_exists($name, $routeName)) {
                $route = $routeName[$name];
                $tempArray = explode('/', trim($route['pattern'], '/'));
//                var_dump($tempArray);

                foreach ($tempArray as $key => &$value) {
                    $value = trim($value, '()');

                }

//                var_dump($params);
//                var_dump($tempArray);
                foreach ($params as $k => $v){
                    //echo "key $k value $v</br>";
                    if (in_array($k, $tempArray)) {
                        $tempKey = array_search($k, $tempArray);
                        $tempArray[$tempKey] = $params[$k];
                    }

                }
                $url = '/' . implode('/', $tempArray);
                if($absoluteUrl) {
                    return $url = 'http://www.wedding.com' . $url;
                } else {
                    return $url;
                }

            }

        }
//        var_dump($tempArray);
    }
    /**
     * @param $data
     * @return array
     */
    public function parseController($data)
    {
        if (strpos($data, ':')) {
            $controllerArray = explode(':', $data);
            $controllerArray = $controllerArray + $this->params;
            return $controllerArray;
        }
    }
    public function printRoutes()
    {
        print_r($this->routes);
    }

    public function deleteRoute($name)
    {
        foreach ($this->routes as $method => $route){
            if(array_key_exists($name, $route)){
                unset($this->routes[$method][$name]);
            }

        }
    }
    public function readRoutes()
    {
        //$this->routes = require_once 'routes.php';

    }

    public function saveRoutes()
    {
        //file_put_contents ( 'routes.php', '<?php return '.var_export( $this->routes, true ).";\n");
    }
    public function getParams()
    {
        return $this->params;
    }
}
