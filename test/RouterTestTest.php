<?php
use SmartRouting\Router;
use SmartRouting\Routing\Request;

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/8/16
 * Time: 6:09 PM
 */
class RouterTestTest extends PHPUnit_Framework_TestCase
{

    public $router;
    public $rec;

    public function setUp() {
        $this->req = new Request();
    }

    public function testRouter()
    {
       $this->router = new Router($this->req);
    }

    public static function provider()
    {
        return array(
            'first' => array('/home', 'get'),
            'second' => array('/login', 'post'),
        );

    }

    /**
     * Testing addValues returns sum of two values
     * @dataProvider provider
     */
    public function testGetRoute($uri, $method)
    {
        $route = new \SmartRouting\Route();
        $this->router = new Router($this->req);
        $this->router->getRoute($route);
        $route = $this->getMock("Route");
        $route->expects($this->any())
            ->method("finedRoute")
            ->with($this->equalTo($uri, '2'));
    }


}
