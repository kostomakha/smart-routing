<?php return array (
  'GET' => 
  array (
    'default' => 
    array (
      'pattern' => '/',
      'controller' => 'Main:index',
    ),
    'profile' => 
    array (
      'pattern' => '/user/(id)/(name)/(sex)',
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
