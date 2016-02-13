<?php

/**
 * Created by PhpStorm.
 * User: roach
 * Date: 13.02.16
 * Time: 19:55
 */
class RoutesTest
{
    public function providerAddRoute()
    {
        return array(
            'default' => array('default', '/', 'default:index', 'GET'),

            'contacts' => array(
                '/contacts',
                'get',
                'contacts',
                '/contacts',
                'contacts:showcontacts',
                'GET',
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

    /**
     *@dataProvider providerRouter
     */
}