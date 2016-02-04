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
        if (array_key_exists($name, $this->routes[$method])){

            $this->deleteRoute($name);
        }

        $this->routes[$method][$name] = array(
            'pattern' => $pattern,
            'controller' => $controller,
        );

        $this->saveRoutes();
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
        //var_dump($routes);

        foreach ($routes as $key => $value){
            if(in_array($uri, $value)) {
                return $this->parseController(end($value));
            }
        }

        return $this->match($uri, $method);

    }
    /**
     *
     *
     * @param $uri
     * @param $method
     * @return array
     */
    public function match($uri, $method)
    {
        //var_dump($uri);
        foreach ($this->routes[$method] as $route) {
            foreach ($route as $key => $value) {
                if (strpos($value, '(')) {
                    //var_dump($value);
                    $data = substr($value, strpos($value, '(') + 1, -1);
                    //var_dump($data);
                    if (array_key_exists($data, $this->patterns)) {
                        $value = str_replace('(' . $data . ')', $this->patterns[$data], $value);
                        if (preg_match('\'' . $value . '\'', $uri, $match)) {
                            //var_dump($match);
                            return $this->parseController(end($route));
                        }
                    }
                } else {
                    //var_dump($uri);
                    return $this->parseController($uri);
                }
            }
        }
        return true;
    }

    public function buildRoute($name, array $params = array(), $absalute = false)
    {
        //var_dump($params);
        foreach ($this->routes as $key => $value) {
            if (array_key_exists($name, $value )) {
                $array = $value[$name];
                $data = substr($array['pattern'],strpos($array['pattern'], '(')+1, -1);
                if(array_key_exists($data, $params)){
                    $query = str_replace('(' . $data . ')', $params[$data], $array['pattern']);

                    if($absalute){
                        return $url = $this->request->getHost() . $query;
                    }
                    return $query;
                }

            }
        }
    }
    /**
     * @param $data
     * @return array
     */
    public function parseController($data)
    {
        if (strpos($data, ':')){
            $controllerArray = explode(':', $data);
            return $controllerArray;
        } else {
            $controllerArray = explode('/', trim($data, '/'));
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
}