<?php
use SmartRouting\Route;

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 07.02.16
 * Time: 19:29
 */

class RouterTest extends PHPUnit_Framework_TestCase
{
    protected $route;

    protected function setUp()
    {
        $this->route = new Route();

    }

    public static function provider()
    {
        return array(
            'zeros' => array(0, 0, 0, 0),
            'numbers' => array(1, 1, 1, 1),
            'default' => array('default', '/', 'DefaultController:actionIndex', 'GET'),
            'error' => array('404', '404', 'DefaultController:actionIndex'),
            'one plus one' => array(1, 1, 3)
        );

    }
/**
* Testing addValues returns sum of two values
* @dataProvider provider
*/
    public function testAddRoute($name, $pattern, $controller, $method = 'GET') {

        $expected = true;
        $actual = $this->route->add($name, $pattern, $controller, $method);
        $this->assertEquals($expected, $actual);

    }

    protected function tearDown()
    {
        unset($this->route);
    }

}
