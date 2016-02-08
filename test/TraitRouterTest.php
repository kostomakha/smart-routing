<?php
use SmartRouting\Router;
/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/8/16
 * Time: 4:36 PM
 */
class TraitRouterTest extends PHPUnit_Framework_TestCase
{
    private $request;

    public function setUp() {
        //$this->statusService = new Request();
    }

    public function testConcreteMethod()
    {
        $mock = $this->getMockBuilder('Router')->disableOriginalConstructor()->getMock();


    }
}
