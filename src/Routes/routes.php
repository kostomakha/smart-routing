<?php return array (
  'GET' => 
  array (
    'default' => 
    array (
      'pattern' => '/',
      'controller' => 'default:index',
    ),
    'contacts' => 
    array (
      'pattern' => '/contacts',
      'controller' => 'contacts:showcontacts',
    ),
    'content1' => 
    array (
      'pattern' => '/category/php/loops-switches',
      'controller' => 'content',
    ),
    'category1' => 
    array (
      'pattern' => '/category/(category)/(course)',
      'controller' => 'category:course',
    ),
    'profile' => 
    array (
      'pattern' => '/user/(id:num)/(name:string)/(sex:num)',
      'controller' => 'user:getuser',
    ),
    'profile2' => 
    array (
      'pattern' => '/user2/(id:num)/(name:string?)/(sex:num?)',
      'controller' => 'user:getuser',
    ),
  ),
  'POST' => 
  array (
  ),
  'PUT' => 
  array (
  ),
  'DELETE' => 
  array (
  ),
  'PATCH' => 
  array (
  ),
);
