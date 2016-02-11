<?php
use SmartRouting\Router;
use SmartRouting\Route;
use SmartRouting\Request;

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/8/16
 * Time: 6:09 PM
 */
class RouterTestTest extends PHPUnit_Framework_TestCase
{

    public function providerRouter()
    {
        return array(
            'zeros' => array(0, 0, 0, 0, 0, 0, 'ErrorController', 'ActionError'),
            'numbers' => array(1, 1, 1, 1, 1, 1, 'ErrorController', 'ActionError'),
            'default' => array('/', 'get', 'default', '/', 'default:index', 'GET', 'DefaultController', 'actionIndex'),
            'contacts' => array(
                '/contacts',
                'get',
                'contacts',
                '/contacts',
                'contacts:showcontacts',
                'GET',
                'ContactsController',
                'actionShowcontacts'
            ),
            'content1' => array(
                '/category/php/loops-switches',
                'get',
                'content1',
                '/category/php/loops-switches',
                'content',
                'GET',
                'ContentController',
                'actionIndex'
            ),
            'category1' => array(
                '/category/java/oop',
                'get',
                'category1',
                '/category/(category)/(course)',
                'category:course',
                'GET',
                'CategoryController',
                'actionCourse'
            ),
            'profile' => array(
                '/user/123/Jhon/1',
                'get',
                'profile',
                '/user/(id:num)/(name:string)/(sex:num)',
                'user:getuser',
                'GET',
                'UserController',
                'actionGetuser'
            ),
            'profile2' => array(
                '/user2/123/Jhon',
                'get',
                'profile2',
                '/user2/(id:num)/(name:string?)/(sex:num?)',
                'user:getuser',
                'GET',
                'UserController',
                'actionGetuser'
            )
        );
    }

//        /user2/123/Jhon
//
//        user2/[0-9]+/[a-zA-Z]+(/[a-zA-Z]+)?
//    (id:num) [0-9]+
//        (name:string?) [\*]+
//        (sex:num?)
//    }

//    public function providerRouteAdd()
//    {
//        return array(
//            'default' => array('default', '/', 'default:index', 'GET'),
//            'contacts' => array('contacts', '/contacts', 'contacts:showcontacts', 'GET', 'ContactsController', 'actionShowcontacts'),
//            'namedRoute' => array('namedRoute', '/info/about/team', 'about:team', 'GET', 'AboutController', 'actionTeam'),
//
//            'allCategories' => array('allCategories', '/categories', 'categories:getallcategories', 'GET', 'ContentController', 'actionIndex'),
//
//            'allParamsRoute1' => array('anyParamsRoute1', '/categories/(category)/(lang)/(course)', 'content', 'GET', 'ContentController', 'actionIndex'),
//
//            'someParamsRoute' => array('anyParamsRoute', '/categories/(category)/(lang)/(course?)', 'content', 'GET', 'ContentController', 'actionIndex'),
//
//            'someParamsRoute' => array('anyParamsRoute', '/categories/(category):(string)/(lang)/(course?):(num)', 'content', 'GET', 'ContentController', 'actionIndex'),
//
//            'someParamsRoute2' => array('anyParamsRoute2', '/categories/(category)/(lang?)/(course?)', 'content', 'GET', 'ContentController', 'actionIndex'),
//
//            'someParamsRoute2' => array('anyParamsRoute2', '/categories/(category?)/(lang?)/(course?)', 'content', 'GET', 'ContentController', 'actionIndex'),
//
//            'someParamsRoute2' => array('anyParamsRoute2', '/categories/(category?):(num)/(lang?)/(course?)', 'content', 'GET', 'ContentController', 'actionIndex'),
//
//            'category1' => array('/category/java/oop', 'get', 'category1', '/category/(category)/(course)', 'category:course', 'GET', 'CategoryController', 'actionCourse'),
//            'profile' => array('/user/123/Jhon/1', 'get', 'profile', '/user/(id)/(name)/(sex)', 'user:getuser', 'GET', 'UserController', 'actionGetuser')
//        );
//    }

    /**
     * Testing addValues returns sum of two values
     * @dataProvider providerRouter
     */

    public function testGetRoute($path, $methodR, $name, $pattern, $controller, $method, $expectedController, $expectedAction)
    {
        $route = new \SmartRouting\Route();

        $route->add($name, $pattern, $controller, $method);

        $request = $this->getMockBuilder('SmartRouting\Request')
            ->setMethods(array('getUri', 'getPath', 'getMethod'))
            ->getMock();

        $uri = $this->getMockBuilder('SmartRouting\Routing\Uri')
            ->setMethods(array('getPath'))
            ->getMock();

        $request->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($methodR));

        $request->expects($this->any())
            ->method('getUri')
            ->will($this->returnSelf());

        $request->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path));

        $router = new \SmartRouting\Router($request);


        $router->getRoute($route);

        $this->assertEquals($expectedController, $router->getController());
        $this->assertEquals($expectedAction, $router->getAction());
        var_dump($router->getParams());

        var_dump($route->buildRoute($name));
    }
}
