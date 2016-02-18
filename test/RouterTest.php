<?php
use SmartRouting\Router;
use SmartRouting\Route;
use SmartRouting\Routes;
use SmartRouting\Request;

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 2/8/16
 * Time: 6:09 PM
 */
class RouterTest extends PHPUnit_Framework_TestCase
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
            'contacts2' => array(
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
                'post',
                'content1',
                '/category/php/loops-switches',
                'content',
                'post',
                'ContentController',
                'actionIndex'
            ),
            'category1' => array(
                '/categories/java/oop',
                'get',
                'category1',
                '/categories/(category)/(course)',
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

    public function testGetRoute($path, $methodR, $name, $pattern, $controller, $method, $expectedController, $expectedAction)
    {

        Routes::add($name, $pattern, $controller, $method);

        $request = $this->getMockBuilder('SmartRouting\Request')
            ->setMethods(array('getUri', 'getMethod'))
            ->getMock();

        $uri = $this->getMockBuilder('SmartRouting\Routing\Uri')
            ->setMethods(array('getPath'))
            ->getMock();

        $request->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($methodR));

        $request->expects($this->any())
            ->method('getUri')
            ->will($this->returnValue($uri));

        $uri->expects($this->any())
            ->method('getPath')
            ->will($this->returnValue($path));


        $router = new Router($request);
        var_dump(Routes::getRoutes());
        var_dump($router->getRoute()->getAction());
        $this->assertEquals($expectedController, $router->getRoute()->getController());
        $this->assertEquals($expectedAction, $router->getRoute()->getAction());

        var_dump($router->getParams());
    }

    public function providerBuildRoute()
    {
        return array(
           'contacts' => array(
                'contacts',
                '',
                '',
                '',
                '',
                '',
                '',
                '/contacts'
            ),
            'content1' => array(
                'content1',
                '',
                '',
                '',
                '',
                '',
                '',
                '/category/php/loops-switches'
            ),
            'category1' => array(
                'category1',
                'category',
                'python',
                'course',
                'Loops',
                '',
                '',
                '/categories/python/Loops'
            ),
            'profile' => array(
                'profile',
                'id',
                '1156',
                'name',
                'Ivan',
                'sex',
                '1'.
                '/user/1156/Ivan/1'
            ),
            'profile2' => array(
                'profile2',
                'id',
                '123',
                'name',
                'Jhon',
                '',
                '',
                '/user2/123/Jhon'
            )
        );
    }

    /**
     * @dataProvider providerBuildRoute
     *
     * @param $name
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $expected
     * @throws \SmartRouting\Routing\Exception\RoutingException
     */
    public function testBuildRoute($name, $paramName1, $param1, $paramName2, $param2, $paramName3, $param3, $expected)
    {
        $route = new Route();

//        $url = $route->buildRoute($name, [$paramName1 => $param1, $paramName2 => $param2], false);
//
//        $this->assertEquals($expected, $url);

        $url = $route->buildRoute($name, [$paramName1 => $param1, $paramName2 => $param2, $paramName3 => $param3], false);

        $this->assertEquals($expected, $url);


    }

}
