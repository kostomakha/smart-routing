<?php

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 13.02.16
 * Time: 19:55
 */
class RouteTest extends PHPUnit_Framework_TestCase
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
}