<?php

namespace Itcourses\Core\Router;
use Itcourses\Core\Router\Helpers\TraitRoute;

/**
 * Class Route
 * @package Itcourses\Core
 */
abstract class AbstractRoute
{
    use TraitRoute;
    /**
     * Array with predefined methods
     * @var array
     */
    protected $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
        'PATCH' => array(),
        'HEAD' => array(),
    );
    /**
     * Array with patterns for preg_match
     * @var array
     */
    protected $patterns = array(
        'num' => '[0-9]+',
        'string' => '[a-zA-Z0-9\.\-_%]+'
    );


    public function __construct()
    {
        $this->add('default','/','Main:index', 'GET');
    }

    /**
     * read routes from file
     */

    public function readRoutes()
    {
        $this->routes = include ROOT . '/Core/routes.php';

    }

    public function saveRoutes()
    {
        file_put_contents ( ROOT . '/Core/routes.php', '<?php return '.var_export( $this->routes, true ).";\n");
    }

}