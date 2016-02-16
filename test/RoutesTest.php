<?php

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 13.02.16
 * Time: 19:55
 */

use SmartRouting\Routes;


class RoutesTest extends PHPUnit_Framework_TestCase
{
    public function providerRouter()
    {
        return array(
            'zeros' => array(0, 0, 0, 0, 0, 0, 'ErrorController', 'ActionError'),
            'numbers' => array(1, 1, 1, 1, 1, 1, 'ErrorController', 'ActionError'),
            'default' => array('default', '/', 'default:index', 'GET', 'DefaultController', 'actionIndex'),
            'contacts' => array(
                'contacts',
                '/contacts',
                'contacts:showcontacts',
                'GET'
            ),
            'contacts2' => array(
                'contacts',
                '/contacts',
                'contacts:showcontacts',
                'GET'
            ),
            'content1' => array(
                'content1',
                '/category/php/loops-switches',
                'content',
                'GET'
            ),
            'category1' => array(
                'category1',
                '/category/(category)/(course)',
                'category:course',
                'GET'
            ),
            'profile' => array(
                'profile',
                '/user/(id:num)/(name:string)/(sex:num)',
                'user:getuser',
                'GET'
            ),
            'profile2' => array(
                'profile2',
                '/user2/(id:num)/(name:string?)/(sex:num?)',
                'user:getuser',
                'GET'
            )
        );
    }

    /**
     * Testing addValues returns sum of two values
     * @dataProvider providerRouter
     *
     * @param $path
     * @param $methodR
     * @param $name
     * @param $pattern
     * @param $controller
     * @param $method
     * @param $expectedController
     * @param $expectedAction
     */

    public function testRoutesAddRoute($name, $pattern, $controller, $method) {
        Routes::add($name, $pattern, $controller, $method);

        $routes = Routes::getInstance();
        $reflection = new ReflectionClass($routes);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true); // now we can modify that :)
        $instance->setValue(null, null); // instance is gone


        var_dump($routes = Routes::getInstance());
        var_dump($routes->getRoutes());
        $routes = new ReflectionClass($routes);
        $instance = $reflection->getProperty('routes');
        $instance->setAccessible(true); // now we can modify that :)
        $routesArray = $routes->getStaticPropertyValue('routes');


        //$routes = Routes::getInstance();
        $this->assertArrayHasKey($name, $routesArray[$method]);

    }
}