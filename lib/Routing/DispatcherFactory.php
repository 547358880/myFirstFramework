<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/18
 * Time: 13:38
 */
namespace Cake\Routing;

use Cake\Core\App;

class DispatcherFactory
{
    protected static $_stack = array();

    public static function create()
    {
        $dispatcher = new Dispatcher();
        foreach (static::$_stack as $middleware) {
            $dispatcher->addFilter($middleware);
        }
        return $dispatcher;
    }

    /*
     *
     */
    public static function add($filter, array $options = array())
    {
        if (is_string($filter)) {
            $filter = static::_createFilter($filter, $options);
        }
        static::$_stack[] = $filter;
        return $filter;
    }

    /*
     * Create an instance of filter
     */
    protected static function _createFilter($name, $options)
    {
        $className = App::className($name, 'Routing/Filter', 'Filter');
        if (!$className) {

        }
        return new $className($options);
    }
}